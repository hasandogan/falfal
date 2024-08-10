import { useEffect, useState } from 'react';
import { Dashboard } from '../services/dashboard/dashboard';
import { IFortune } from '../services/dashboard/models/IFortune';

const HomeLogic = () => {
  const [fortunes, setFortunes] = useState<IFortune[]>([]);

  const getDashboardData = async () => {
    try {
      const response = await Dashboard();
      console.log('response', response);
    } catch (error) {
      console.error('API Error:', error);
    }
  };
  useEffect(() => {
    getDashboardData();
  }, []);

  return { fortunes };
};
export default HomeLogic;
