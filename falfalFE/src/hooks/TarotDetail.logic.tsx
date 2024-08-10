import { useEffect, useState } from 'react';
import { GetTarot } from '../services/tarot/get-tarot';
import { IGetTarotResponse } from '../services/tarot/models/IGetTarotResponse';

const TarotDetailLogic = (id: string | string[] | undefined) => {
  const [tarot, setTarot] = useState<IGetTarotResponse | null>(null);

  const getTarotDetailData = async (tarotId: string) => {
    try {
      const response = await GetTarot({ id: tarotId });
      if (response?.data) {
        console.log('res', response);
        setTarot(response.data);
      }
    } catch (error) {
      console.error('API Error:', error);
    }
  };

  useEffect(() => {
    if (typeof id === 'string' && id) {
      getTarotDetailData(id);
    }
  }, [id]);

  return { tarot };
};

export default TarotDetailLogic;
