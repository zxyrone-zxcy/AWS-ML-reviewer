<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuizPagesTest extends TestCase
{
    public function test_public_pages_load(): void
    {
        foreach (['/', '/quiz', '/about'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_home_mentions_the_exam(): void
    {
        $this->get('/')->assertSee('MLA-C01');
    }

    public function test_study_guide_page_loads(): void
    {
        $this->get('/guide')->assertOk()->assertSee('Bedrock Guardrails')->assertSee('AgentCore');
    }

    public function test_question_bank_is_valid(): void
    {
        $response = $this->getJson('/question-bank')->assertOk();
        $bank = $response->json();

        $this->assertNotEmpty($bank);

        $ids = array_column($bank, 'id');
        $this->assertSame(count($ids), count(array_unique($ids)), 'Question ids must be unique');

        foreach ($bank as $q) {
            $count = count($q['options']);
            $this->assertGreaterThanOrEqual(4, $count, "Question {$q['id']} needs at least 4 options");
            $this->assertLessThanOrEqual(6, $count, "Question {$q['id']} has too many options");

            // `correct` is one index, or a list of indexes for choose-two/multi-select questions.
            $correct = (array) $q['correct'];
            $this->assertNotEmpty($correct, "Question {$q['id']} needs a correct answer");
            $this->assertSame(count($correct), count(array_unique($correct)), "Question {$q['id']} repeats a correct index");
            foreach ($correct as $i) {
                $this->assertIsInt($i);
                $this->assertGreaterThanOrEqual(0, $i);
                $this->assertLessThan($count, $i, "Question {$q['id']} correct index is out of range");
            }

            $this->assertNotEmpty($q['takeaway']);
            foreach ($q['options'] as $opt) {
                $this->assertNotEmpty($opt['text']);
                $this->assertNotEmpty($opt['why']);
            }
        }
    }
}
