import { useEffect, useState } from 'react';
import { Dashboard } from '../services/dashboard/dashboard';
import { IPendingProcess } from '../services/dashboard/models/IDashboardResponse';
import { IFortune } from '../services/dashboard/models/IFortune';

const HomeLogic = () => {
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [fortunes, setFortunes] = useState<IFortune[]>([]);
  const [pendingProcess, setPendingProcess] = useState<IPendingProcess | null>(
    null
  );

  const getDashboardData = async () => {
    setIsLoading(true);
    try {
      const response = await Dashboard();
      if (response.data) {
        setFortunes(response.data?.fortunes);
        setPendingProcess(response.data.pendingProcess);
      }
      console.log('response', response);
    } catch (error) {
      console.error('API Error:', error);
    } finally {
      setIsLoading(false);
    }
  };
  useEffect(() => {
    getDashboardData();
  }, []);

  return { isLoading, fortunes, pendingProcess };
};
export default HomeLogic;
