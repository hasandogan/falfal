import { useEffect, useRef, useState } from 'react';
import { GetCoffee } from '../services/coffee/get-coffee'; // Coffee servisi

export interface ICoffeeDetail {
  id: number;
  images: string[];
  message: string;
}

const CoffeeDetailLogic = (id: string | string[] | undefined) => {
  const [coffeeResult, setCoffeeResult] = useState<ICoffeeDetail | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const coffeeRef = useRef<HTMLDivElement>(null);

  const getCoffeeDetailData = async (coffeeId: string) => {
    setIsLoading(true);
    try {
      const response = await GetCoffee({ id: coffeeId });
      if (response?.data) {
        const serializedForState: ICoffeeDetail = {
          id: response.data.id,
          message: response.data.message,
          images: response.data.images.images || [], // Images'ı düz bir dizi olarak al
        };
        setCoffeeResult(serializedForState);
      }
    } catch (error) {
      console.error('API Error:', error);
    }
    setIsLoading(false);
  };

  useEffect(() => {
    if (typeof id === 'string' && id) {
      getCoffeeDetailData(id);
    }
  }, [id]);

  return { coffeeResult, isLoading, coffeeRef };
};

export default CoffeeDetailLogic;
