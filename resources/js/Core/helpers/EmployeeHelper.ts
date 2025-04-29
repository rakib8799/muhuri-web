import axios from "axios";

export const getManagers = async (branchId: string) => {
    try{
        return await axios.get(`/branches/${branchId}/employee-options`);
    }catch(err: any){
        return err;
    }
};

export const setManagers = async (branchId: string, managers: any, formData:any, propsEmployee: any) => {
    try {
        const response = await getManagers(branchId);
        const {employeeOptions, managerId} = response.data?.data;
        managers.value = employeeOptions;
        if(branchId != propsEmployee?.branch_id){
            formData.manager_id = managerId;
        }
        else{
            formData.manager_id = propsEmployee?.manager_id;
        }
    } catch (err: any) {
        managers.value = [];
    }
}

export const setLeaveManagers = async (branchId: string, leaveManagers: any, formData:any, propsEmployee: any) => {
    try {
        const response = await getManagers(branchId);
        const {employeeOptions, managerId} = response.data?.data;
        leaveManagers.value = employeeOptions;
        if(branchId != propsEmployee?.branch_id){
            formData.leave_manager_id = managerId;
        }
        else{
            formData.leave_manager_id = propsEmployee?.leave_manager_id;
        }
    } catch (err: any) {
        leaveManagers.value = [];
    }
}
