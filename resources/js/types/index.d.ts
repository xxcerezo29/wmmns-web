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
    }>;
}

export interface Roles {
    id: number;
    name: string;
}

export interface Permissions {
    id: number;
    name: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>
> = T & {
    auth: {
        user: User;
        roles: Array<Roles>;
        permissions: Permissions;
    };
    flash: {
        message: string;
        status: string;
    };
    currentRouteName: string;
};
