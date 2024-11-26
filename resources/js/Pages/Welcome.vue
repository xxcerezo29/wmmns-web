<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import "../../css/LandingPage/animate.css";
// import "../../css/LandingPage/main.css";
import "lineicons/web-font/css/icons.css";

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
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}

const isNavbarOpen = ref(false);

const navbarToggle = () => {
    isNavbarOpen.value = !isNavbarOpen.value;
};

const onScroll = () => {
    const HeaderNavBar = document.querySelector(".navigation") as HTMLElement;
    if (HeaderNavBar) {
        const sticky = HeaderNavBar.offsetTop;

        if (window.pageYOffset > sticky) {
            HeaderNavBar.classList.add("sticky");
        } else {
            HeaderNavBar.classList.remove("sticky");
        }
    }
    const backToTop = document.querySelector(".back-to-top") as HTMLElement;
    if (backToTop)
        if (
            document.body.scrollTop > 50 ||
            document.documentElement.scrollTop > 50
        ) {
            backToTop.style.display = "flex";
        } else {
            backToTop.style.display = "none";
        }
};

onMounted(() => {
    window.addEventListener("scroll", onScroll);
});
</script>

<template>
    <div>
        <Head title="Welcome" />
        <header class="relative">
            <div class="navigation fixed top-0 left-0 w-full z-30 duration-300">
                <div class="container">
                    <nav
                        class="navbar py-2 navbar-expand-lg flex justify-between items-center relative duration-300"
                    >
                        <Link href="/">
                            <img
                                src="/wmmns-logo.png"
                                alt="WMMNS"
                                class="h-24 rounded-full"
                            />
                        </Link>
                        <button
                            @click="navbarToggle"
                            :class="isNavbarOpen ? 'active' : ''"
                            class="navbar-toggler focus:outline-none block lg:hidden"
                            type="button"
                            data-toggle="collapse"
                            data-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent"
                            aria-expanded="false"
                            aria-label="Toggle navigation"
                        >
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                        </button>

                        <div
                            class="navbar-collapse hidden lg:block duration-300 shadow absolute top-10 left-0 mt-full bg-white z-20 px-5 py-3 w-full lg:static lg:bg-transparent lg:shadow-none"
                            :class="isNavbarOpen ? 'show' : ''"
                            id="navbarSupportedContent"
                        >
                            <ul
                                class="navbar-nav mr-auto justify-center items-center lg:flex gap-2"
                            >
                                <li class="nav-item">
                                    <Link href="/" class="page-scroll"
                                        >Home</Link
                                    >
                                </li>
                                <li class="nav-item">
                                    <a
                                        href="#system-feature"
                                        class="page-scroll"
                                        >System Feature</a
                                    >
                                </li>
                            </ul>
                        </div>
                        <div
                            v-if="canLogin"
                            class="header-btn hidden sm:block sm:absolute sm:right-0 sm:mr-16 lg:static lg:mr-0"
                        >
                            <Link
                                v-if="!$page.props.auth.user"
                                class="text-blue-600 border border-blue-600 px-10 py-3 rounded-full duration-300 hover:bg-blue-600 hover:text-white"
                                :href="route('login')"
                                >Login</Link
                            >
                            <Link
                                v-else
                                :href="route('dashboard')"
                                class="text-blue-600 border border-blue-600 px-10 py-3 rounded-full duration-300 hover:bg-blue-600 hover:text-white"
                            >
                                Dashboard</Link
                            >
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
                        <h2
                            class="text-4xl font-bold leading-snug text-gray-700 mb-10 wow fadeInUp"
                            data-wow-delay="1s"
                        >
                            Waste Management with Mobile Nofitication System<br
                                class="hidden lg:block"
                            />for CENRO
                        </h2>
                        <div class="text-center mb-10 wow fadeInUp">
                            <a
                                href="/apk/wmmns-release.apk"
                                rel="nofollow"
                                class="btn"
                                >Download the Mobile App. Now</a
                            >
                        </div>
                        <div
                            class="text-center wow fadeInUp"
                            data-wow-delay="1.6s"
                        >
                            <img
                                class="img-fluid mx-auto"
                                src="garbage-truck.png"
                                alt=""
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="system-feature" class="py-24">
            <div class="container">
                <div class="text-center">
                    <h2
                        class="mb-12 section-heading wow fadeInDown"
                        data-wow-delay="0.3s"
                    >
                        System Feature
                    </h2>
                </div>
                <div class="flex flex-wrap">
                    <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3">
                        <div class="m-4 wow fadeInRight" data-wow-delay="0.3s">
                            <div class="icon text-5xl">
                                <i class="lni lni-cog"></i>
                            </div>
                            <div>
                                <h3 class="service-title">
                                    Centralized Waste Management
                                </h3>
                                <p class="text-gray-600">
                                    The system provided a centralized system
                                    that will efficiently manage and process
                                    waste collection to disposal, ensuring a
                                    cleaner environment.
                                </p>
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
                                <p class="text-gray-600">
                                    monitoring garbage truck routes ensuring
                                    proper designation of routes and schedules.
                                </p>
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
                                <p class="text-gray-600">
                                    The system provides a real-time updates and
                                    reports on the status of waste collection.
                                </p>
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
                                <p class="text-gray-600">
                                    The system also has a spatial mapping that
                                    will determine the density of the residents’
                                    complaints on that barangay.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3">
                        <div class="m-4 wow fadeInRight" data-wow-delay="0.3s">
                            <div class="icon text-5xl">
                                <i class="lni lni-cog"></i>
                            </div>
                            <div>
                                <h3 class="service-title">
                                    Mobile Application Notification
                                </h3>
                                <p class="text-gray-600">
                                    The system will also have a notification
                                    feature whenever there’s a schedule of
                                    garbage collection on their barangays.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.container {
    width: 100%;
}

.show {
    display: block;
}

.toggler-icon {
    display: block;
    --bg-opacity: 1;
    background-color: #4a5568;
    background-color: rgba(74, 85, 104, var(--bg-opacity));
    position: relative;
    transition-duration: 300ms;
    height: 2px;
    width: 30px;
    margin: 6px 0;
}
.page-scroll.active,
.page-scroll:hover {
    --text-opacity: 1;
    color: #3182ce;
    color: rgba(49, 130, 206, var(--text-opacity));
}

@media (min-width: 640px) {
    .container {
        max-width: 640px;
    }
}

@media (min-width: 768px) {
    .container {
        max-width: 768px;
    }
}

@media (min-width: 1024px) {
    .container {
        max-width: 1024px;
    }
}

@media (min-width: 1280px) {
    .container {
        max-width: 1280px;
    }
}

.active > .toggler-icon:nth-child(1) {
    --transform-translate-x: 0;
    --transform-translate-y: 0;
    --transform-rotate: 0;
    --transform-skew-x: 0;
    --transform-skew-y: 0;
    --transform-scale-x: 1;
    --transform-scale-y: 1;
    transform: translateX(var(--transform-translate-x))
        translateY(var(--transform-translate-y)) rotate(var(--transform-rotate))
        skewX(var(--transform-skew-x)) skewY(var(--transform-skew-y))
        scaleX(var(--transform-scale-x)) scaleY(var(--transform-scale-y));
    --transform-rotate: 45deg;
    top: 7px;
}

.active > .toggler-icon:nth-child(2) {
    opacity: 0;
}

.active > .toggler-icon:nth-child(3) {
    top: -8px;
    transform: rotate(135deg);
}

.sticky {
    position: -webkit-sticky;
    position: sticky;
}

.sticky {
    position: fixed;
    --bg-opacity: 1;
    background-color: #fff;
    background-color: rgba(255, 255, 255, var(--bg-opacity));
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.btn {
    --text-opacity: 1;
    color: #fff;
    color: rgba(255, 255, 255, var(--text-opacity));
    --bg-opacity: 1;
    background-color: #3182ce;
    background-color: rgba(49, 130, 206, var(--bg-opacity));
    padding-left: 2.5rem;
    padding-right: 2.5rem;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    border-radius: 9999px;
    transition-duration: 300ms;
}

.btn:hover {
    --bg-opacity: 1;
    background-color: #4299e1;
    background-color: rgba(66, 153, 225, var(--bg-opacity));
}
.section-heading {
    font-size: 2.25rem;
    --text-opacity: 1;
    color: #4a5568;
    color: rgba(74, 85, 104, var(--text-opacity));
    font-weight: 700;
    letter-spacing: 0.025em;
}
.icon {
    margin-bottom: 1rem;
    --text-opacity: 1;
    color: #3182ce;
    color: rgba(49, 130, 206, var(--text-opacity));
}
.service-title {
    --text-opacity: 1;
    color: #2d3748;
    color: rgba(45, 55, 72, var(--text-opacity));
    font-weight: 600;
    font-size: 1.125rem;
    display: block;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
}
.feature-title {
    --text-opacity: 1;
    color: #2d3748;
    color: rgba(45, 55, 72, var(--text-opacity));
    font-weight: 500;
    display: block;
    margin-bottom: 0.75rem;
}
.container {
    margin-left: auto;
    margin-right: auto;
    padding-left: 1.25rem;
    padding-right: 1.25rem;
}
</style>
