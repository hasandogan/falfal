'use client';
import { IProfileRequest } from '@/services/profile/models/profile/IProfileRequest';
import { setProfile, getProfile } from '@/services/profile/profile'; // GET isteği için ekleme
import { useState, useEffect } from 'react';

const ProfileLogic = () => {
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
  enum MaritalStatus {
    '1' = 'married',
    '2' = 'single',
    '3' = 'engaged',
    '4' = 'widowed',
  }

  enum Gender {
    '1' = 'male',
    '2' = 'female',
    '3' = 'prefer not to say',
  }

  enum HasChildren {
    '1' = 'yes',
    '2' = 'no',
  }

  enum EmploymentStatus {
    '1' = 'full-time',
    '2' = 'part-time',
    '3' = 'unemployed',
  }

  enum EducationLevel {
    '1' = 'primary school',
    '2' = 'secondary school',
    '3' = 'high school',
    '4' = 'vocational school',
    '5' = 'university',
    '6' = 'master`s or doctoral',
  }

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

  useEffect(() => {
    const fetchProfileData = async () => {
      try {
        const response = await getProfile(); // Profil verilerini GET isteği ile al
        setProfileData(response); // Gelen veriyi profileData state'ine aktar
      } catch (error) {
        console.error('API Error:', error);
      }
    };

    fetchProfileData();
  }, []);

  const handleChange = (
      e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setProfileData((prevState) => ({ ...prevState, [name]: value }));
  };

  const handleSubmit = async (event: React.FormEvent) => {
    event.preventDefault();
    try {
      const response = await setProfile(profileData);
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

export default ProfileLogic;
