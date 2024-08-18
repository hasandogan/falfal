import { useEffect, useRef, useState } from 'react';
import CardList from '../../../tarot2.json';
import { GetTarot } from '../services/tarot/get-tarot';

export interface ITarotDetail {
  id: number;
  message: string;
  selectedCards: ITarotCard[];
}

export interface ITarotCard {
  id: number;
  name: string;
  front: string;
  revert: string;
  enFront: string;
  enRevert: string;
  image: string;
  result?: boolean;
}

const TarotDetailLogic = (id: string | string[] | undefined) => {
  const [tarotResult, setTarotResult] = useState<ITarotDetail | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const tarotRef = useRef<HTMLDivElement>(null);

  const getTarotDetailData = async (tarotId: string) => {
    setIsLoading(true);
    try {
      const response = await GetTarot({ id: tarotId });
      if (response?.data) {
        const newList = CardList.filter((x) =>
          response.data?.selectedCards.includes(x.id)
        );
        const serializedForState: ITarotDetail = {
          id: response.data.id,
          message: response.data.message,
          selectedCards: newList,
        };
        setTarotResult(serializedForState);
      }
    } catch (error) {
      console.error('API Error:', error);
    }
    setIsLoading(false);
  };

  useEffect(() => {
    if (typeof id === 'string' && id) {
      getTarotDetailData(id);
    }
  }, [id]);

  return { tarotResult, isLoading, tarotRef };
};

export default TarotDetailLogic;
