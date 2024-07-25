'use client';
import { IProfileRequest } from '@/services/profile/models/profile/IProfileRequest';
import { setProfile, getProfile } from '@/services/profile/profile'; // GET isteği için ekleme
import { useState, useEffect } from 'react';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
const ProfileLogic = () => {
  const initialForm: IProfileRequest = {
    email: '',
    name: '',
    lastName: '',
    password: '',
    relationShip: 'single',
    gender: 'prefer not to say',
    hasChildren: 'no',
    birthDate: '',
    birthTime: '',
    jobStatus: 'unemployed',
    educationLevel: 'primary school',
    occupation: '',
    town: '',
  };
  enum RelationShip {
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

  enum jobStatus {
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

  const relationShipOptions = [
    { value: RelationShip['1'], label: 'Evli' },
    { value: RelationShip['2'], label: 'Bekar' },
    { value: RelationShip['3'], label: 'Nişanlı' },
    { value: RelationShip['4'], label: 'Dul' },
  ];

  const genderOptions = [
    { value: Gender['1'], label: 'Erkek' },
    { value: Gender['2'], label: 'Kadın' },
    { value: Gender['3'], label: 'Belirtmek İstemiyorum' },
  ];

  const hasChildrenOptions = [
    { value: HasChildren['1'], label: 'Evet' },
    { value: HasChildren['2'], label: 'Hayır' },
  ];

  const jobStatusOptions = [
    { value: jobStatus['1'], label: 'Tam Zamanlı' },
    { value: jobStatus['2'], label: 'Yarı Zamanlı' },
    { value: jobStatus['3'], label: 'Çalışmıyor' },
  ];

  const educationLevelOptions = [
    { value: EducationLevel['1'], label: 'İlköğretim' },
    { value: EducationLevel['2'], label: 'Orta Öğretim' },
    { value: EducationLevel['3'], label: 'Lise' },
    { value: EducationLevel['4'], label: 'Meslek Yüksek Okulu' },
    { value: EducationLevel['5'], label: 'Üniversite' },
    { value: EducationLevel['6'], label: 'Yüksek Lisans ve Doktora' },
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
      toast.success('Profilini başarıyla güncelledin. Artık fallarına daha detaylı bakabileceğiz. 🎉');
    } catch (error) {
      toast.error('Bir şeyler yanlış gitti, bizden kaynaklı bir hata olabilir. Lütfen tekrar dene. 🙏');

    }
  };

  return {
    handleChange,
    handleSubmit,
    profileData,
    relationShipOptions,
    genderOptions,
    jobStatusOptions,
    hasChildrenOptions,
    educationLevelOptions,
  };
};

export default ProfileLogic;
