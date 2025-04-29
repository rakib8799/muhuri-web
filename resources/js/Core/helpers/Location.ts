import { ref, watch } from "vue";
import axios from "axios";

export function Location(formData: any) {
  const divisions = ref<any[]>([]);
  const districts = ref<any[]>([]);
  const upazilas = ref<any[]>([]);
  const unions = ref<any[]>([]);
  const data = {
    division_id: formData.division_id || '',
    district_id: formData.district_id || '',
    upazila_id: formData.upazila_id || '',
    union_id: formData.union_id || ''
};

  const fetchDivisions = async () => {
    const response = await axios.get('/api/v1/divisions');
    divisions.value = response.data;
  };

  const fetchDistricts = async (divisionId: number) => {
    const response = await axios.get(`/api/v1/divisions/${divisionId}/districts`);
    districts.value = response.data;
  };

  const fetchUpazilas = async (districtId: number) => {
    const response = await axios.get(`/api/v1/districts/${districtId}/upazilas`);
    upazilas.value = response.data;
  };

  const fetchUnions = async (upazilaId: number) => {
    const response = await axios.get(`/api/v1/upazilas/${upazilaId}/unions`);
    unions.value = response.data;
  };

  // Watchers for dependent fields
  watch(() => formData.division_id, (divisionId: any) => {
    data.district_id = formData.district_id || '';
    data.upazila_id = formData.upazila_id || '';
    data.union_id = formData.union_id || '';
    fetchDistricts(divisionId);
  });

  watch(() => formData.district_id, (districtId: any) => {
    data.upazila_id = formData?.upazila_id || '';
    data.union_id = formData?.union_id || '';
    fetchUpazilas(districtId);
  });

  watch(() => formData.upazila_id, (upazilaId: any) => {
    data.union_id = formData?.union_id || '';
    fetchUnions(upazilaId);
  });

  const loadInitialData = async () => {
    fetchDivisions();
    if (data?.division_id) {
      fetchDistricts(formData.division_id);
    }
    if (data?.district_id) {
      fetchUpazilas(formData.district_id);
    }
    if (data?.upazila_id) {
      fetchUnions(formData.upazila_id);
    }
  };

  return {
    divisions,
    districts,
    upazilas,
    unions,
    loadInitialData
  };
}
