import { useState } from 'react';
import { IProfileRequest } from '../../services/profile/models/profile/IProfileRequest';
import { Profile } from '../../services/profile/profile';

const useProfileLogic = () => {
  const initialForm: IProfileRequest = {
    email: '',
    name: '',
    lastName: '',
    password: '',
    maritalStatus: 'single',
    age: '',
    gender: 'prefer not to say',
    hasChildren: 'no',
    birthDate: '',
    birthTime: '',
    employmentStatus: 'unemployed',
    educationLevel: 'primary school',
    occupation: '',
    city: '',
  };

  const maritalStatusOptions = [
    { value: 'married', label: 'Evli' },
    { value: 'single', label: 'Bekar' },
    { value: 'engaged', label: 'Nişanlı' },
    { value: 'widowed', label: 'Dul' },
  ];

  const genderOptions = [
    { value: 'male', label: 'Erkek' },
    { value: 'female', label: 'Kadın' },
    { value: 'prefer not to say', label: 'Belirtmek İstemiyorum' },
  ];

  const hasChildrenOptions = [
    { value: 'yes', label: 'Evet' },
    { value: 'no', label: 'Hayır' },
  ];

  const employmentStatusOptions = [
    { value: 'full-time', label: 'Tam Zamanlı' },
    { value: 'part-time', label: 'Yarı Zamanlı' },
    { value: 'unemployed', label: 'Çalışmıyor' },
  ];

  const educationLevelOptions = [
    { value: 'primary school', label: 'İlköğretim' },
    { value: 'secondary school', label: 'Orta Öğretim' },
    { value: 'high school', label: 'Lise' },
    { value: 'vocational school', label: 'Meslek Yüksek Okulu' },
    { value: 'university', label: 'Üniversite' },
    {
      value: 'master`s or doctoral',
      label: 'Yüksek Lisans ve Doktora',
    },
  ];
  const [profileData, setProfileData] = useState<IProfileRequest>(initialForm);

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setProfileData((prevState) => ({ ...prevState, [name]: value }));
  };
  const handleSubmit = async (event: React.FormEvent) => {
    event.preventDefault();
    try {
      const response = await Profile(profileData);
      console.log('API Response:', response);
      alert('Profil başarıyla güncellendi.');
    } catch (error) {
      console.error('API Error:', error);
      alert('Profil güncelleme sırasında bir hata oluştu.');
    }
  };
  return {
    handleChange,
    handleSubmit,
    profileData,
    maritalStatusOptions,
    genderOptions,
    employmentStatusOptions,
    hasChildrenOptions,
    educationLevelOptions,
  };
};

export default useProfileLogic;
