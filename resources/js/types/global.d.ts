import axios, { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from '.';
import { InertiaAppProps } from '@inertiajs/vue3/types/app';
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

declare global {
    interface Window {
        axios: AxiosInstance;
        HSStaticMethods: IStaticMethods;
        Pusher: Pusher
        Echo: Echo
    }


    var route: typeof ziggyRoute;
}

declare module "vue" {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module "@inertiajs/core"{
    interface PageProps extends InertiaAppProps, AppPageProps {}
}