<script setup>
import { ref, nextTick, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import Navbar from '@/Components/Navbar.vue';


defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});

const activeSection = ref('home');

const scrollToSection = (section) => {
  activeSection.value = section;
  nextTick(() => {
    const target = document.getElementById(section);
    if (target) {
      window.scrollTo({
        top: target.offsetTop - 80, // Adjust for fixed navbar
        behavior: 'smooth'
      });
    }
  });
};


const updateActiveSection = () => {
  const sections = ['home', 'about', 'resources', 'contact'];
  let currentSection = 'home';

  sections.forEach((section) => {
    const el = document.getElementById(section);
    if (el && window.scrollY >= el.offsetTop - 100) {
      currentSection = section;
    }
  });

  activeSection.value = currentSection;
};

onMounted(() => {
  window.addEventListener('scroll', updateActiveSection);
});

const form = useForm({
  name: '',
  email: '',
  message: '',
});

const openArticles = () => {
  // Redirect to articles page
  window.location.href = route('articles.index');
};

const openVideos = () => {
  // Open a modal or navigate to videos page
  window.location.href = route('videos.index');
};

const openResources = () => {
  // Navigate to resources download page
  window.location.href = route('resources.index');
};

const submitForm = () => {
  form.post(route('contact.submit'), {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Message Sent!',
        text: 'Your message has been sent successfully. We will get back to you soon!',
        confirmButtonColor: '#3b82f6',
      });
      form.reset();
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: 'Please check your inputs and try again.',
        confirmButtonColor: '#fca5a5',
      });
    },
  });
};
</script>

<template>
  <div class="min-h-screen flex flex-col bg-blue-100 text-gray-900">
    <!-- Navbar Component -->
    <Navbar :canLogin="canLogin" :canRegister="canRegister" @scrollTo="scrollToSection" />
    <!-- Hero Section -->
<section id="home" class="min-h-screen flex flex-col md:flex-row items-center justify-center text-center md:text-left px-6 py-32 mt-16 bg-blue-100 text-white">
  <div class="md:w-1/2">
    <p class="uppercase tracking-widest text-lg text-black">Empower Your Mind, Elevate Your Life</p>
    <h1 class="text-5xl font-extrabold text-blue-500 leading-tight">Your Journey to Better Mental Health Starts Here</h1>
    <p class="text-xl text-black mt-4">Counsel & Care offers comprehensive tools for mental health assessment, screening, and appointment scheduling with experienced Guidance Counselors to support your mental health journey.</p>
    <Link :href="route('register')" class="mt-6 inline-block bg-blue-500 text-white px-6 py-3 rounded-full text-lg font-semibold hover:bg-blue-600 transition-all transform hover:scale-105 focus:ring-4 focus:ring-blue-400 focus:outline-none">
      Get Started Today
    </Link>
  </div>
  <div class="md:w-1/2 mt-8 md:mt-0 flex justify-center items-center">
  <img src="https://aestheticsjournal.com/wp-content/uploads/2024/10/3-1.jpg"
       alt="Mental Health Illustration"
       class="w-[90%] max-w-3xl rounded-3xl shadow-2xl object-cover">
</div>
</section>


    <!-- About Section -->
<div id="about" class="min-h-screen flex flex-col items-center text-center bg-blue-100 text-white px-3 py-3">
  <h2 class="text-4xl font-extrabold text-blue-400 animate-fade-in">About Counsel & Care</h2>

  <p class="text-lg text-black mt-4 max-w-3xl animate-fade-in delay-100">
    Counsel & Care was developed by mental health professionals to bridge the gap between professional care and daily mental wellness.
    We believe that understanding your mental health should be straightforward and supportive. Our platform provides <span class="font-semibold">confidential screening, comprehensive assessments,</span> and <span class="font-semibold">valuable resources</span> — all designed to help  you on your mental health journey.
  </p>

  <!-- Features Section -->
  <div class="w-full max-w-5xl text-center animate-slide-in">
    <h3 class="text-2xl font-semibold text-blue-400 mt-3">Our Features</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
      <div class="bg-blue-100 p-6 rounded-lg shadow-md">
        <h4 class="text-xl font-semibold text-blue-400">Mental Health Screening</h4>
        <p class="text-black mt-2 text-justify" style="text-justify: inter-character;">Confidential screenings to identify potential mental health concerns like anxiety, depression, and stress, offering helpful insights for next steps.</p>
      </div>
      <div class="bg-blue-100 p-6 rounded-lg shadow-md">
        <h4 class="text-xl font-semibold text-blue-400">Comprehensive Assessments</h4>
        <p class="text-black mt-2 text-justify" style="text-justify: inter-character;">In-depth assessments that provide a clearer understanding of your mental health, highlighting areas where professional support may be beneficial.</p>
      </div>
      <div class="bg-blue-100 p-6 rounded-lg shadow-md">
        <h4 class="text-xl font-semibold text-blue-400">Educational Resources</h4>
        <p class="text-black mt-2 text-justify" style="text-justify: inter-character;">Access a collection of evidence-based articles and tools to better understand mental health and develop effective coping strategies.</p>
      </div>
      <div class="bg-blue-100 p-6 rounded-lg shadow-md">
        <h4 class="text-xl font-semibold text-blue-400">Appointment Scheduling</h4>
        <p class="text-black mt-2 text-justify" style="text-justify: inter-character;">Easily schedule appointments with mental health professionals based on your needs, connecting you to the Guidance Counselor for support.</p>
      </div>
      <div class="bg-blue-100 p-6 rounded-lg shadow-md">
        <h4 class="text-xl font-semibold text-blue-400">Privacy & Security</h4>
        <p class="text-black mt-2 text-justify" style="text-justify: inter-character;">Your mental health data is secure. We implement strong privacy measures to protect your information at all times.</p>
      </div>
    </div>
  </div>


  <!-- Call-to-Action Button -->
  <a href="#resources"
     class="mt-2 inline-block bg-blue-500 text-white px-3 py-3 rounded-full text-lg font-semibold hover:bg-blue-600 transition-all transform hover:scale-105 focus:ring-4 focus:ring-blue-400 focus:outline-none"
     onclick="document.getElementById('resources').scrollIntoView({ behavior: 'smooth' }); return false;">
    Explore Resources
  </a>

  <!-- Meet the Team Section -->
  <div class="mt-16 w-full">
    <h3 class="text-3xl font-bold text-blue-400 text-center">Meet Our Team</h3>
    <p class="text-black mt-2 max-w-2xl mx-auto text-center">
      Our dedicated team of professionals is committed to providing the best system for mental health support.
    </p>

    <!-- Team Members Grid -->
    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl mx-auto">
      <!-- Team Member 1 -->
      <div class="bg-blue-100 p-6 rounded-lg shadow-lg text-center">
        <img src="/images/2x2.jpg" class="w-32 h-32 mx-auto rounded-full shadow-lg" alt="Your Name">
        <h4 class="text-black mt-2 max-w-2xl mx-auto text-center">John Michael Vincent Baldon</h4>
        <p class="text-blue-400">Researcher and Web Development</p>
      </div>

      <!-- Team Member 2 -->
      <div class="bg-blue-100 p-6 rounded-lg shadow-lg text-center">
        <img src="/images/2x2-Paul.jpg" class="w-32 h-32 mx-auto rounded-full shadow-lg" alt="Team Member">
        <h4 class="text-black mt-2 max-w-2xl mx-auto text-center">Paul Christian Orioque</h4>
        <p class="text-blue-400">Researcher and Documentation</p>
      </div>

            <!-- Team Member 3 -->
            <div class="bg-blue-100 p-6 rounded-lg shadow-lg text-center">
        <img src="/images/2x2-Matt.jpg" class="w-32 h-32 mx-auto rounded-full shadow-lg" alt="Team Member">
        <h4 class="text-black mt-2 max-w-2xl mx-auto text-center">Matt John Santos</h4>
        <p class="text-blue-400">Researcher and Machine Learning Integration</p>
      </div>
            <!-- Team Member 4 -->
            <div class="bg-blue-100 p-6 rounded-lg shadow-lg text-center md:col-start-2">
    <img src="/images/2x2-Brix.png" class="w-32 h-32 mx-auto rounded-full shadow-lg" alt="Team Member">
    <h4 class="text-black mt-2 max-w-2xl mx-auto text-center">Brix Arabejo Mirote</h4>
    <p class="text-blue-400">Guidance Counselor</p>
  </div>
    </div>
  </div>
</div>

    <!-- Resources Section -->
<section id="resources" class="min-h-screen bg-blue-100 text-white px-6 py-6">
  <div class="max-w-6xl mx-auto text-center">
    <h2 class="text-4xl font-extrabold text-blue-400 ">Mental Health Resources</h2>
    <p class="text-lg text-black mt-4 max-w-3xl mx-auto">
      Access evidence-based mental health guides, self-help articles, and expert advice to support your well-being.
    </p>
  </div>

  <!-- Section Image -->
<div class="mt-3 flex justify-center">
  <img src="https://static.vecteezy.com/system/resources/previews/003/332/223/non_2x/mental-health-awareness-day-concept-free-vector.jpg"
    alt="Mental Health Awareness"
    class="w-full max-w-xs rounded-lg shadow-lg">
</div>


  <!-- Resource Categories -->
  <div class="mt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
  <!-- Articles -->
  <div class="bg-blue-100 p-6 rounded-lg shadow-lg hover:bg-blue-400 transition">
    <h3 class="text-xl font-semibold text-blue-700">Mental Health Articles</h3>
    <p class="text-black mt-2 text-justify">
        Discover expert-written articles on mental health challenges and solutions for college students, covering topics like anxiety, depression, and stress management.
    </p>
    <a @click.prevent="openArticles" class="mt-4 inline-block text-blue-700 font-semibold hover:text-blue-900 transition cursor-pointer">
      Explore Articles →
    </a>
  </div>

  <!-- Videos -->
  <div class="bg-blue-100 p-6 rounded-lg shadow-lg hover:bg-blue-400 transition">
    <h3 class="text-xl font-semibold text-blue-700">Video Library</h3>
    <p class="text-black mt-2 text-justify">
        Watch curated videos that provide practical advice on coping strategies, emotional resilience, and self-care to support mental wellness in college life.
    </p>
    <a @click.prevent="openVideos" class="mt-4 inline-block text-blue-700 font-semibold hover:text-blue-900 transition cursor-pointer">
      Watch Now →
    </a>
  </div>

  <!-- Self-Help Toolkit -->
  <div class="bg-blue-100 p-6 rounded-lg shadow-lg hover:bg-blue-400 transition">
    <h3 class="text-xl font-semibold text-blue-700">Self-Help Toolkit</h3>
    <p class="text-black mt-2 text-justify">
        Access downloadable worksheets and exercises based on Cognitive Behavioral Therapy (CBT) to help improve mental health and build healthy coping skills.
    </p>
    <a @click.prevent="openResources" class="mt-4 inline-block text-blue-700 font-semibold hover:text-blue-900 transition cursor-pointer">
      Download Resources →
    </a>
  </div>
</div>

  <!-- Call to Action -->
  <div class="mt-2 text-center">
    <a href="#contact"
   class="mt-8 inline-block bg-blue-400 text-whote px-6 py-3 rounded-full text-lg font-semibold hover:bg-blue-500 transition-all transform hover:scale-105 focus:ring-4 focus:ring-blue-400 focus:outline-none"
   onclick="document.getElementById('contact').scrollIntoView({ behavior: 'smooth' }); return false;">
   Get Support Now
</a>
  </div>
</section>


    <!-- Contact Section -->
    <section id="contact" class="min-h-screen flex flex-col md:flex-row items-center bg-blue-100 text-white px-6 py-6 md:py-12">
      <div class="md:w-1/2 bg-blue-100 text-black rounded-lg shadow-lg p-8 max-w-lg mx-auto">
        <h2 class="text-3xl font-bold bg-blue-500 text-black px-4 py-2 rounded-t-lg">Contact Us</h2>
        <p class="mt-4">
          For further questions, including partnership opportunities, please email
          <a href="mailto:counselcare01@gmail.com" class="text-blue-700 font-semibold">
            counselcare01@gmail.com
          </a>
          or use our contact form.
        </p>
        <form @submit.prevent="submitForm" class="mt-6">
          <div>
            <label for="name" class="block text-sm font-semibold">Full Name</label>
            <input id="name" v-model="form.name" type="text" class="w-full border-b-2 border-blue-700 bg-transparent py-2 outline-none" placeholder="Your Full Name">
            <p v-if="form.errors.name" class="text-red-400 text-sm mt-1">{{ form.errors.name }}</p>
          </div>

          <div class="mt-4">
            <label for="email" class="block text-sm font-semibold">Email</label>
            <input id="email" v-model="form.email" type="email" class="w-full border-b-2 border-blue-700 bg-transparent py-2 outline-none" placeholder="Your Email">
            <p v-if="form.errors.email" class="text-red-400 text-sm mt-1">{{ form.errors.email }}</p>
          </div>

          <div class="mt-4">
            <label class="block text-sm font-semibold">How can we help you?</label>
            <textarea v-model="form.message" class="w-full border-b-2 border-blue-700 bg-transparent py-2 outline-none" rows="4" placeholder="Describe your problem in at least 250 characters"></textarea>
            <p v-if="form.errors.message" class="text-red-400 text-sm mt-1">{{ form.errors.message }}</p>
          </div>

          <button type="submit" :disabled="form.processing" class="mt-6 w-full bg-blue-400 text-white px-4 py-2 rounded-full hover:bg-blue-500 transition-all">
            {{ form.processing ? 'Sending...' : 'Send Message' }}
          </button>
        </form>
      </div>
    </section>
  </div>
</template>

<style>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in {
  animation: fadeIn 1s ease-in-out;
}
</style>
