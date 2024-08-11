export interface paginated<T> {
    current_page: number
        data: Array<T>;
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        links: Array<{
            active: boolean;
            label: string;
            url: string;
        }>;
        next_page_ul:string;
        path: string;
        per_page: number;
        prev_page_url: string;
        to: number;
        total: number;
}

export interface Roles {
    id: number;
    name:string;
    permissions: Array<Permissions>;
}

export interface Permissions {
    id: number;
    name: string;
}

export interface ICities {
    code: number,
    name: string,
    regionCode: string,
}