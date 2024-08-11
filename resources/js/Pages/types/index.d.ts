export interface User {
    id: number;
    firstname: string;
    middlename: string;
    lastname: string;
    email: string;
    barangay: string;
    email_verified_at: string;
    roles: Array<{
        id: number;
        name: string;
    }>
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
        stores: number;
    };
    flash: {
        message: string;
        status: string;
    };
    currentRouteName:string;
}