<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ResourceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Resource', [
            'articles' => [
                [
                    'title' => 'Mental Health Workbooks and Exercises',
                    'content' => 'Explore these useful resources to download workbooks and exercises designed to improve mental health. Click the links below to visit the respective pages and download the workbooks.',
                    'link' => 'https://www.therapistaid.com/worksheets/cognitive-distortions',
                ],
                [
                    'title' => 'Fact or Opinion Worksheet',
                    'content' => 'A worksheet to help you distinguish between facts and opinions in your thinking. Click below to download.',
                    'link' => 'https://www.therapistaid.com/worksheets/fact-or-opinion',
                ],
                [
                    'title' => 'CBT Triangle Worksheet',
                    'content' => 'Learn how to use the Cognitive Behavioral Therapy triangle with this helpful worksheet.',
                    'link' => 'https://www.therapistaid.com/worksheets/cbt-triangle',
                ],
                [
                    'title' => 'Worry Exploration Questions',
                    'content' => 'Explore your worries using the questions in this worksheet to challenge negative thinking patterns.',
                    'link' => 'https://www.therapistaid.com/worksheets/worry-exploration-questions',
                ],
                [
                    'title' => 'Core Beliefs Information Sheet',
                    'content' => 'Understand your core beliefs and how they affect your behavior and emotions.',
                    'link' => 'https://www.therapistaid.com/worksheets/core-beliefs-info-sheet',
                ],
                [
                    'title' => 'Core Beliefs: Examining Evidence',
                    'content' => 'Use this worksheet to examine the evidence for and against your core beliefs.',
                    'link' => 'https://www.therapistaid.com/worksheets/core-beliefs-examining-evidence',
                ],
                [
                    'title' => 'Socratic Questioning Worksheet',
                    'content' => 'This worksheet helps you use Socratic questioning to examine your thoughts and beliefs.',
                    'link' => 'https://www.therapistaid.com/worksheets/socratic-questioning',
                ],
                [
                    'title' => 'Behavioral Experiment Worksheet',
                    'content' => 'Test your thoughts and beliefs with a behavioral experiment using this worksheet.',
                    'link' => 'https://www.therapistaid.com/worksheets/behavioral-experiment',
                ],
                [
                    'title' => 'Cognitive Model Example and Practice',
                    'content' => 'Practice applying the Cognitive Model to real-life situations with this helpful worksheet.',
                    'link' => 'https://www.therapistaid.com/worksheets/cognitive-model-example-practice',
                ],
            ]
        ]);
    }
}
