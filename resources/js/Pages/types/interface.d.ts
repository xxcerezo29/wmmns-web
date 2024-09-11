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

export interface Schedule {
    barangay: string;
    day: string;
    id: number;
    route_id: number;
    time: string;
    truck_id: number;
    truck: Truck;
    route: Route;
}

export interface Driver {
    id: number;
    firstname: string;
    middlename: string;
    lastname: string;
    barangay: string;
    truck_id: number;
    assigned_truck: Truck;
    email: string;
    mobile_number: string;
}

export interface Route{
    id: number;
    name: string;
    barangay: string;
    waypoint: string;
}

export interface IChartData {
    labels: Array<string>; // Corrected to 'labels'
    datasets: Array<{
        label: string;
        data: Array<number>;
        backgroundColor: string | Array<string>;
        borderColor?: string | Array<string>;
        borderWidth?: number;
    }>
}


export interface Truck {
    id: number;
    barangay: string;
    plate_number: string;
    driver: Driver;
}

export interface Resident{
    id: number;
    firstname: string;
    middlename: string;
    lastname: string;
    line1: string;
    line2: string;
    barangay: string;
    city: string;
    province: string;
    country: string;
    email: string;
    email_verified_at: string;
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

export interface IComplaint {
    id: number;
    reference_number: string;
    resident_id: number;
    schedule_id: number;
    report_type: string;
    location: string;
    barangay: string;
    description: string;
    status: string;
    photo_url: string;
    resolved_at: string;
    resident: Resident;
    schedule: Schedule;
}