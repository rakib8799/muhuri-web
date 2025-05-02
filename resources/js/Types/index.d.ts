export interface User {
    id: number;
    name: string;
    email: string;
    mobile_number: string;
    mobile_number_verified_at: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};
