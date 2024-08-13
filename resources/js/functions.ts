import { usePage } from "@inertiajs/vue3";

export function hasPermission(input: string) {
    const permissions = usePage().props.auth.permissions;
    return (
        Array.isArray(permissions) &&
        permissions.some(
            (permission) =>
                permission.name.toLowerCase() === input.toLowerCase()
        )
    );
}
export function hasRole(input: string) {
    const roles = usePage().props.auth.roles;
    return (
        Array.isArray(roles) &&
        roles.some((role) => role.name.toLowerCase() === input.toLowerCase())
    );
}