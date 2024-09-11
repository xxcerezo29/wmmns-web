<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import '../../css/LandingPage/animate.css';
import '../../css/LandingPage/main.css';
import 'lineicons/web-font/css/icons.css';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

const isNavbarOpen = ref(false);

const navbarToggle = () => {
    isNavbarOpen.value = !isNavbarOpen.value;
}

const onScroll = () => {
    const HeaderNavBar = document.querySelector(".navigation") as HTMLElement;
    const sticky = HeaderNavBar.offsetTop;

    if (window.pageYOffset > sticky) {
        HeaderNavBar.classList.add("sticky");
    } else {
        HeaderNavBar.classList.remove("sticky");
    }

    const backToTop = document.querySelector(".back-to-top") as HTMLElement;
    if (backToTop)
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            backToTop.style.display = "flex";
        } else {
            backToTop.style.display = "none";
        }
}

onMounted(() => {
    window.addEventListener('scroll', onScroll);
})

</script>

<template>

    <Head title="Welcome" />
    <header class="relative">
        <div class="navigation fixed top-0 left-0 w-full z-30 duration-300">
            <div class="container">
                <nav class="navbar py-2 navbar-expand-lg flex justify-between items-center relative duration-300">
                    <Link href="/">
                    <img src="" alt="WMMNS" />
                    </Link>
                    <button @click="navbarToggle" :class="isNavbarOpen ? 'active' : ''"
                        class="navbar-toggler focus:outline-none block lg:hidden" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                    </button>

                    <div class=" navbar-collapse hidden lg:block duration-300 shadow absolute  top-10 left-0 mt-full bg-white z-20 px-5 py-3 w-full lg:static lg:bg-transparent lg:shadow-none"
                        :class="isNavbarOpen ? 'show' : ''" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto justify-center items-center lg:flex gap-2">
                            <li class="nav-item">
                                <Link href="/" class="page-scroll">Home</Link>
                            </li>
                            <li class="nav-item">
                                <a href="#system-feature" class="page-scroll">System Feature</a>
                            </li>

                        </ul>
                    </div>
                    <div v-if="canLogin"
                        class="header-btn hidden sm:block sm:absolute sm:right-0 sm:mr-16 lg:static lg:mr-0">
                        <Link  v-if="!$page.props.auth.user"
                            class="text-blue-600 border border-blue-600 px-10 py-3 rounded-full duration-300 hover:bg-blue-600 hover:text-white"
                            :href="route('login')">Login</Link>
                        <Link v-else :href="route('dashboard')"
                            class="text-blue-600 border border-blue-600 px-10 py-3 rounded-full duration-300 hover:bg-blue-600 hover:text-white">
                        Dashboard</Link>
                        <!-- <Link v-if="canRegister"
                            class="text-blue-600 border border-blue-600 px-10 py-3 rounded-r-full duration-300 hover:bg-blue-600 hover:text-white"
                            :href="route('register')">Register</Link> -->
                    </div>
                    <!-- <div v-else class="header-btn hidden sm:block sm:absolute sm:right-0 sm:mr-16 lg:static lg:mr-0">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="text-blue-600 border border-blue-600 px-10 py-3 rounded-full duration-300 hover:bg-blue-600 hover:text-white">
                        Dashboard</Link>
                    </div> -->

                </nav>
            </div>
        </div>
    </header>
    <section class="bg-blue-100 pt-48 pb-10">
        <div class="container">
            <div class="flex justify-between">
                <div class="w-full text-center">
                    <h2 class="text-4xl font-bold leading-snug text-gray-700 mb-10 wow fadeInUp" data-wow-delay="1s">
                        Waste Management with Mobile Nofitication System<br class="hidden lg:block">for CENRO</h2>
                    <div class="text-center mb-10 wow fadeInUp">
                        <a href="#" rel="nofollow" class="btn">Download the Mobile App. Now</a>
                    </div>
                    <div class="text-center wow fadeInUp" data-wow-delay="1.6s">
                        <img class="img-fluid mx-auto" src="garbage-truck.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="system-feature" class="py-24">
        <div class="container">
            <div class="text-center">
                <h2 class="mb-12 section-heading wow fadeInDown" data-wow-delay="0.3s">System Feature</h2>
            </div>
            <div class="flex flex-wrap">
                <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3">
                    <div class="m-4 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="icon text-5xl">
                            <i class="lni lni-cog"></i>
                        </div>
                        <div>
                            <h3 class="service-title">Centralized Waste Management</h3>
                            <p class="text-gray-600">The system provided a centralized system that will efficiently
                                manage and process waste collection to disposal, ensuring a cleaner environment.</p>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3">
                    <div class="m-4 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="icon text-5xl">
                            <i class="lni lni-cog"></i>
                        </div>
                        <div>
                            <h3 class="service-title">Truck Monitoring</h3>
                            <p class="text-gray-600">monitoring garbage truck routes ensuring proper designation of
                                routes and schedules.</p>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3">
                    <div class="m-4 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="icon text-5xl">
                            <i class="lni lni-cog"></i>
                        </div>
                        <div>
                            <h3 class="service-title">Reporting</h3>
                            <p class="text-gray-600">The system provides a real-time updates and reports on the status
                                of waste collection.</p>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3">
                    <div class="m-4 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="icon text-5xl">
                            <i class="lni lni-cog"></i>
                        </div>
                        <div>
                            <h3 class="service-title">Spatial Mapping</h3>
                            <p class="text-gray-600">The system also has a spatial mapping that will determine the
                                density of the residents’ complaints on that barangay. </p>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3">
                    <div class="m-4 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="icon text-5xl">
                            <i class="lni lni-cog"></i>
                        </div>
                        <div>
                            <h3 class="service-title">Mobile Application Notification</h3>
                            <p class="text-gray-600">The system will also have a notification feature whenever there’s a
                                schedule of garbage collection on their barangays.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
