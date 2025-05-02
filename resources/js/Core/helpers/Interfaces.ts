export interface ISubscriptionHistory {
    id: number;
    subscription_plan: any;
    start_date: string;
    end_date: string;
    final_price: number;
    is_trial_taken: boolean;
}

export interface IEmployee {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    attendance_date: string;
    check_in: string;
    attendance_status: string;
}

export interface IPaymentMethod {
    name: string;
    slug: string;
    logo: string;
}
