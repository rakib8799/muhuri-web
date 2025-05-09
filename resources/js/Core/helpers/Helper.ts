import axios from "axios";
import moment from 'moment-timezone';
import { getAssetPath } from "@/Core/helpers/Assets";

export const getLanguages = async () => {
    try{
        return await axios.get(`/language-options`);
    }catch(err: any){
        return err;
    }
};

export const getFullName = (obj: any) => {
    const { first_name, last_name, name } = obj;
    return name? name : `${first_name} ${last_name}`;
};

export const getInitials = (obj: any) => {
    const { first_name, last_name, name } = obj;
    const firstNameInitial = first_name?.charAt(0)?.toUpperCase();
    const lastNameInitial = last_name?.charAt(0)?.toUpperCase();
    const nameInitial = name?.charAt(0)?.toUpperCase();
    return name? nameInitial : firstNameInitial + lastNameInitial;
}

export const getUppercase = (obj: any) => {
    const { code } = obj;
    return code.toUpperCase();
}

export const caplitalize = (str: any) => {
    return str.charAt(0).toUpperCase() + str.slice(1);
};

export const getLogoSrc = (logo: any) => {
    const logoPath = logo as string | undefined;
    if(logoPath) {
      return logoPath;
    } else {
      return 'media/logos/mkr-logo.png';
    }
}

export const getDayNumberFromName = (dayName: string) => {
    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    const dayNumber = days.indexOf(dayName);
    return dayNumber;
}

export const getMonthNames = () => {
    return [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
}

export const addOneDayToEndDate = (dateTo: string) => {
    const endDate = new Date(dateTo);
    endDate.setDate(endDate.getDate() + 1);
    return endDate.toISOString().split('T')[0];
}

export const getCurrentYearRange = () => {
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), 0, 1);
    const lastDay = new Date(now.getFullYear(), 11, 31);

    const formatDate = (date: Date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    return {
        firstDay: formatDate(firstDay),
        lastDay: formatDate(lastDay)
    };
}

const storedTimezone = localStorage.getItem('timezone');
const timezone = storedTimezone ? JSON.parse(storedTimezone) : null;
const storedDateFormat = localStorage.getItem('date_format');
const dateFormat = storedDateFormat ? JSON.parse(storedDateFormat) : null;

export const extractDateTime = (dateTime: Date) => {
    const dateTimeFormat = dateFormat?.replace('g', 'h')?.replace('i', 'mm');
    return moment(dateTime).tz(timezone).format(dateTimeFormat);
};

export const extractDate = (date: Date) => {
    if (!date) return ''; // Handle null/undefined dates

    const datePattern = dateFormat
        ? dateFormat.replace(/g|i|A|h|H|mm|ss|:/g, '').trim()
        : 'YYYY-MM-DD'; // Default fallback format

    return moment(date).tz(timezone || 'UTC').format(datePattern);
};

export const extractTime = (time: Date) => {
    return moment(time).tz(timezone).format('hh:mm A');
};
