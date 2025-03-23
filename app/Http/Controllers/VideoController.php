<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class VideoController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index(): Response
    {
        return Inertia::render('Videos', [
            'videos' => [
                [
                    'title' => '5 Mental Health Coping Skills',
                    'description' => 'Learn positive coping skills to manage stress and improve mental well-being.',
                    'link' => 'https://www.youtube.com/watch?v=PcKyBMGYWO4',
                ],
                [
                    'title' => '3 Mental Wellness Tips',
                    'description' => 'Discover three essential tips to support your mental well-being.',
                    'link' => 'https://www.youtube.com/watch?v=SBJ19oO8WDc',
                ],
                [
                    'title' => 'Coping Mechanisms: Healthy vs. Unhealthy',
                    'description' => 'Understand the difference between healthy and unhealthy coping mechanisms.',
                    'link' => 'https://www.youtube.com/watch?v=cPoqxmaEhL4',
                ],
                [
                    'title' => '10 Top Tips to Improve Your Mental Health',
                    'description' => 'Explore ten practical tips to boost your mental health.',
                    'link' => 'https://www.youtube.com/watch?v=clZutBrYX28',
                ],
                [
                    'title' => 'Coping Skills for Mental Health',
                    'description' => 'Access a playlist of videos offering various coping skills for mental health.',
                    'link' => 'https://www.youtube.com/playlist?list=PLiUrrIiqidTURwqfebGIy2x4F4I9yzXPJ',
                ],
                [
                    'title' => 'Coping Skills for Kids: Managing Feelings & Emotions',
                    'description' => 'Learn coping strategies suitable for children dealing with emotions.',
                    'link' => 'https://www.youtube.com/watch?v=Vs-MyQgfH3A',
                ],
                [
                    'title' => '5 Habits That BOOST Your Mental Health',
                    'description' => 'Discover habits that can significantly enhance your mental health.',
                    'link' => 'https://www.youtube.com/watch?v=yci8v0vP15o',
                ],
                [
                    'title' => 'It\'s OK to Not Be OK: Coping Skills',
                    'description' => 'Understand the importance of coping skills in managing mental health.',
                    'link' => 'https://www.youtube.com/watch?v=axeois9AZWU',
                ],
                [
                    'title' => 'A to Z of Coping Strategies',
                    'description' => 'Explore a comprehensive range of coping strategies from A to Z.',
                    'link' => 'https://www.youtube.com/watch?v=5EXpkVw3fh0',
                ],
                [
                    'title' => 'Resilience and Emotional Well-Being Video Series',
                    'description' => 'Access a series of videos focusing on building resilience and emotional well-being.',
                    'link' => 'https://psychiatry.ucsf.edu/copingresources/videos',
                ],
                [
                    'title' => 'Are Your Coping Mechanisms Healthy? | TED Talk',
                    'description' => 'A TED Talk discussing the effectiveness of various coping mechanisms.',
                    'link' => 'https://www.ted.com/talks/andrew_miki_are_your_coping_mechanisms_healthy',
                ],
                [
                    'title' => 'One Minute of Wellness: Sleep Tips',
                    'description' => 'Quick tips on improving sleep for better mental health.',
                    'link' => 'https://www.healthnet.com/content/healthnet/en_us/health-and-wellness/one-minute-of-wellness.html',
                ],
                [
                    'title' => 'Healthy Coping Strategies for Young People',
                    'description' => 'Strategies tailored for young individuals to cope with mental health challenges.',
                    'link' => 'https://www.mentallyhealthyschools.org.uk/resources/healthy-coping-strategies-video-for-young-people/',
                ],
                [
                    'title' => 'Self-Care Tips for Young People - NHS',
                    'description' => 'Guidance on self-care practices to maintain mental health.',
                    'link' => 'https://www.nhs.uk/every-mind-matters/mental-wellbeing-tips/youth-mental-health/',
                ],
            ]
        ]);
    }
}
?>
