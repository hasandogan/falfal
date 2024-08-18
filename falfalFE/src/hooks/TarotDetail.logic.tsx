import { useEffect, useRef, useState } from 'react';
import CardListJson from '../../../tarot2.json';
import { GetTarot } from '../services/tarot/get-tarot';

export interface ITarotDetail {
  id: number;
  question?: string;
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
  result: boolean; // result'ı zorunlu hale getirdik
}

const CardList: ITarotCard[] = CardListJson as ITarotCard[];

const TarotDetailLogic = (id: string | string[] | undefined) => {
  const [tarotResult, setTarotResult] = useState<ITarotDetail | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const tarotRef = useRef<HTMLDivElement>(null);

  const getTarotDetailData = async (tarotId: string) => {
    setIsLoading(true);
    try {
      const response = await GetTarot({ id: tarotId });
      if (response?.data) {
        const newList: ITarotCard[] = response.data.selectedCards
          .map(({ key, value }) => {
            const card = CardList.find((card) => card.id === key);
            if (card) {
              return {
                ...card,
                result: value ?? false,
              };
            }
            return null;
          })
          .filter((card): card is ITarotCard => card !== null);

        const serializedForState: ITarotDetail = {
          id: response.data.id,
          message: response.data.message,
          selectedCards: newList,
          question: response.data.question,
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
