'use client';
import { getProfile, setProfile } from '@/services/profile/profile'; // GET isteği için ekleme
import { useEffect, useState } from 'react';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { IProfile } from '../services/profile/models/profile/IProfile';
import { EducationLevelEnum } from '../utils/enums/EducationLevelEnum';
import { GenderEnum } from '../utils/enums/GenderEnum';
import { HasChildrenEnum } from '../utils/enums/HasChildrenEnum';
import { JobStatusEnum } from '../utils/enums/JobStatusEnum';
import { RelationShipEnum } from '../utils/enums/RelationShipEnum';
const ProfileLogic = () => {
  const initialForm: IProfile = {
    email: '',
    name: '',
    lastName: '',
    password: '',
    relationShip: '',
    gender: '',
    hasChildren: 'no',
    birthDate: '',
    birthTime: '',
    jobStatus: '',
    educationLevel: '',
    occupation: '',
    town: '',
    country: '',
  };

  const relationShipOptions = [
    { value: RelationShipEnum.married, label: 'Evli' },
    { value: RelationShipEnum.single, label: 'Bekar' },
    { value: RelationShipEnum.engaged, label: 'Nişanlı' },
    { value: RelationShipEnum.widowed, label: 'Dul' },
  ];

  const genderOptions = [
    { value: GenderEnum.male, label: 'Erkek' },
    { value: GenderEnum.female, label: 'Kadın' },
    { value: GenderEnum.preferNotToSay, label: 'Belirtmek İstemiyorum' },
  ];

  const hasChildrenOptions = [
    { value: HasChildrenEnum.yes, label: 'Evet' },
    { value: HasChildrenEnum.no, label: 'Hayır' },
  ];

  const jobStatusOptions = [
    { value: JobStatusEnum.fullTime, label: 'Tam Zamanlı' },
    { value: JobStatusEnum.partTime, label: 'Yarı Zamanlı' },
    { value: JobStatusEnum.unemployed, label: 'Çalışmıyor' },
  ];

  const educationLevelOptions = [
    { value: EducationLevelEnum.primarySchool, label: 'İlköğretim' },
    { value: EducationLevelEnum.secondarySchool, label: 'Orta Öğretim' },
    { value: EducationLevelEnum.highSchool, label: 'Lise' },
    {
      value: EducationLevelEnum.vocationalSchool,
      label: 'Meslek Yüksek Okulu',
    },
    { value: EducationLevelEnum.university, label: 'Üniversite' },
    { value: EducationLevelEnum.doctoral, label: 'Yüksek Lisans ve Doktora' },
  ];

  const [profileData, setProfileData] = useState<IProfile>(initialForm);

  useEffect(() => {
    const fetchProfileData = async () => {
      try {
        const response = await getProfile();
        if (response.data) {
          setProfileData(response.data);
        }
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
      toast.success(
        'Profilini başarıyla güncelledin. Artık fallarına daha detaylı bakabileceğiz. 🎉'
      );
    } catch (error) {
      toast.error(
        'Bir şeyler yanlış gitti, bizden kaynaklı bir hata olabilir. Lütfen tekrar dene. 🙏'
      );
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
