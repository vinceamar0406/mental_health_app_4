<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Articles', [
            'articles' => [
                [
                    'title' => 'Mental Health on College Campuses: Challenges and Solutions',
                    'content' => 'An in-depth look at the mental health challenges faced by college students and potential solutions to address these issues.',
                    'link' => 'https://www.usnews.com/news/education-news/articles/mental-health-on-college-campuses-challenges-and-solutions',
                ],
                [
                    'title' => 'The Mental Health Crisis on College Campuses',
                    'content' => 'Exploring the increasing rates of anxiety, depression, and suicidal ideation among college students, and the overwhelming pressure on faculty and staff.',
                    'link' => 'https://www.nea.org/nea-today/all-news-articles/mental-health-crisis-college-campuses',
                ],
                [
                    'title' => "College Students' Mental Health Improving, More Finding Support",
                    'content' => 'Recent studies indicate a decrease in anxiety, depression, and suicidal thoughts among college students, along with an increase in seeking mental health care.',
                    'link' => 'https://www.insidehighered.com/news/student-success/health-wellness/2024/08/19/experts-weigh-college-student-mental-health-crisis',
                ],
                [
                    'title' => '7 Essential Mental Health Tips for College Students',
                    'content' => 'Practical advice on maintaining mental well-being during college, including self-care, time management, and seeking support.',
                    'link' => 'https://www.mcphs.edu/admission-and-aid/blog/how-to-support-the-mental-health-of-college-students',
                ],
                [
                    'title' => 'Mental Health Concerns in College Students',
                    'content' => 'An overview of common mental health issues among college students and strategies to address them.',
                    'link' => 'https://www.acha.org/college-health-topics/mental-health/',
                ],
                [
                    'title' => 'Why Mental Health Matters for College Students',
                    'content' => 'Discussing the importance of mental health awareness and support for college students.',
                    'link' => 'https://www.psychiatry.org/news-room/apa-blogs/fostering-college-student-mental-health-and-resili',
                ],
                [
                    'title' => 'Mental Health of College Students',
                    'content' => 'A comprehensive look at the mental health challenges faced by college students and the need for effective support systems.',
                    'link' => 'https://www.usnews.com/opinion/articles/2024-10-23/the-pandemic-fueled-a-mental-health-crisis-on-college-campuses-how-can-we-fix-it',
                ],
                [
                    'title' => 'I had 10 panic attacks a day, says student',
                    'content' => "A personal account of a student's struggle with panic attacks and the broader mental health challenges at universities.",
                    'link' => 'https://www.thetimes.co.uk/article/my-students-say-theyre-too-anxious-to-learn-i-dare-not-question-it-lzh93zrrq',
                ],
                [
                    'title' => 'Overdiagnosis of Mental Health Conditions',
                    'content' => 'A discussion on the potential overdiagnosis of mental health conditions and its implications.',
                    'link' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4527955/',
                ],
                [
                    'title' => 'The pandemic reshaped telehealth in San Antonio',
                    'content' => 'An exploration of how the COVID-19 pandemic has transformed telehealth services, particularly in mental health.',
                    'link' => 'https://www.apa.org/monitor/2022/10/mental-health-campus-care',
                ],
                [
                    'title' => '8 Ways Drinking Alcohol Could Give You Anxiety',
                    'content' => 'An examination of how alcohol consumption can lead to increased anxiety levels.',
                    'link' => 'https://health.clevelandclinic.org/mental-health-in-college-students',
                ],
                [
                    'title' => 'Are our "lost boys" really being failed?',
                    'content' => 'A discussion on the challenges faced by young men in the current societal context.',
                    'link' => 'https://www.nea.org/nea-today/all-news-articles/mental-health-crisis-college-campuses',
                ],
                [
                    'title' => 'Being a "night owl" increases your risk of depression',
                    'content' => 'Insights into how late-night habits can elevate the risk of depression and the underlying reasons.',
                    'link' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC9210532/',
                ],
                [
                    'title' => 'The advice young Aussies need to hear',
                    'content' => 'Addressing the mental health challenges among young Australians and the role of social media.',
                    'link' => 'https://www.axios.com/local/pittsburgh/2025/03/20/politics-bullying-harm-lgbtq-youth-pennsylvania',
                ],
            ]
        ]);
    }
}
