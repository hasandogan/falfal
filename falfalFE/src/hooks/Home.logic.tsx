import { useEffect, useState } from 'react';
import { IFortune } from '../services/dashboard/models/IFortune';

const HomeLogic = () => {
  const [fortunes, setFortunes] = useState<IFortune[]>([]);

  const getDashboardData = async () => {
    // const response = await Dashboard();

    const exampleFortunes: IFortune[] = [
      {
        id: 1,
        date: '2015-03-25T12:00:00Z',
        type: 1,
        message: 'Lorem ipsum sit amet',
      },
      {
        id: 2,
        date: '2024-03-25T12:00:00Z',
        type: 1,
        message: 'Lorem ipsum sit amet qwe',
      },
    ];
    setFortunes(exampleFortunes);
  };
  useEffect(() => {
    getDashboardData();
  }, []);

  return { fortunes };
};
export default HomeLogic;
