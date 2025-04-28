<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Str;

class PlagiarismChecker
{
    public function check(string $text): array
    {
        $results = [];
        foreach (Document::all() as $doc) {
            $similarity = $this->cosineSimilarity(
                $this->tfidf($text),
                $this->tfidf($doc->content)
            );

            if ($similarity > 0.3) { // Seuil personnalisable
                $results[] = [
                    'document_id' => $doc->id,
                    'similarity' => round($similarity * 100, 2)
                ];
            }
        }
        return $results;
    }

    private function tfidf(string $text): array
    {
        $terms = array_count_values(str_word_count(Str::lower($text), 1));
        $totalTerms = array_sum($terms);
        return array_map(function ($count) use ($totalTerms) {
            return $count / $totalTerms;
        }, $terms);
    }

    private function cosineSimilarity(array $vector1, array $vector2): float
    {
        $intersection = array_intersect_key($vector1, $vector2);
        $dotProduct = array_sum(array_map(fn($k) => $vector1[$k] * $vector2[$k], array_keys($intersection)));
        $normA = sqrt(array_sum(array_map(fn($x) => $x ** 2, $vector1)));
        $normB = sqrt(array_sum(array_map(fn($x) => $x ** 2, $vector2)));
        return ($normA * $normB) > 0 ? $dotProduct / ($normA * $normB) : 0;
    }

    public function findSimilarSentences(string $text)
    {
        $phrases = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $documents = \App\Models\Document::all();
        $results = [];

        foreach ($phrases as $phrase) {
            $bestMatch = null;
            $bestScore = 0;
            $bestDocId = null;
            $bestDocSentence = '';

            foreach ($documents as $doc) {
                $docSentences = preg_split('/(?<=[.!?])\s+/', $doc->content, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($docSentences as $docSentence) {
                    $similarity = $this->cosineSimilarity(
                        $this->tfidf($phrase),
                        $this->tfidf($docSentence)
                    );
                    if ($similarity > $bestScore) {
                        $bestScore = $similarity;
                        $bestDocId = $doc->id;
                        $bestDocSentence = $docSentence;
                    }
                }
            }

            // On ne garde que les phrases vraiment similaires (> seuil, ex 0.5)
            if ($bestScore > 0.5) {
                $results[] = [
                    'phrase' => $phrase,
                    'similarity' => round($bestScore * 100, 2),
                    'document_id' => $bestDocId,
                    'matched_sentence' => $bestDocSentence,
                ];
            }
        }
        return $results;
    }
}
