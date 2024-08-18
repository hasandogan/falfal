import { useRouter } from 'next/router';
import { useEffect, useRef, useState } from 'react';
import { toast } from 'react-toastify';
import { default as CardListJson } from '../../../tarot2.json';
import { SendTarot } from '../services/tarot/send-tarot';
import { getRandomBoolean } from '../utils/helpers/getRandomBoolean';
import { getUnselectedTarot } from '../utils/helpers/getUnselectedTarot';
import { toRadians } from '../utils/helpers/toRadians';
import { ITarotCard } from './TarotDetail.logic';

const TarotLogic = () => {
  const router = useRouter();
  const maxSelectableCardCount = 7;
  const stackRef = useRef<HTMLDivElement>(null);
  const [isQuestionAsked, setIsQuestionAsked] = useState<boolean>(false);
  const [question, setQuestion] = useState<string>('');
  const [selectedCards, setSelectedCards] = useState<ITarotCard[]>([]);
  const [buttonLoading, setButtonLoading] = useState<boolean>(false);

  const CardList: ITarotCard[] = CardListJson as ITarotCard[];

  const sendQuestion = () => {
    setIsQuestionAsked(true);
  };

  const handleClick = () => {
    const newCards: ITarotCard = getUnselectedTarot(CardList, selectedCards);
    const serializedCard = {
      ...newCards,
      result: getRandomBoolean(),
    };
    setSelectedCards((prev) => [...prev, serializedCard]);
  };

  const handleChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
    const { value } = e.target;
    setQuestion(value);
  };
  const totalCardCount = Array.from(
    { length: maxSelectableCardCount },
    (_, i) => i + 1
  );

  const submitTarotCards = async () => {
    setButtonLoading(true);
    const request = {
      question,
      selectedTarotsCards: selectedCards.map((card) => {
        return {
          key: card.id,
          value: card.result!,
        };
      }),
    };
    try {
      console.log('request', request);
      const response = await SendTarot(request);
      console.log('response', response);
      toast.success(response?.message || 'Falin gönderildi');
      router.push('/home');
    } catch (error: any) {
      console.log('error', error);
      toast.error(error?.response?.data?.message || 'Bir problem oluştu');
    }
    setButtonLoading(false);
  };

  const drawCardCircle = () => {
    const stack = stackRef.current;
    if (stack) {
      const cDiv = stack.children;
      const numberOfCards = cDiv.length;
      const radius = 150;
      const angleIncrement = 360 / numberOfCards;

      for (let i = 0; i < numberOfCards; i++) {
        const angle = toRadians(angleIncrement * i);
        const x = radius * Math.cos(angle);
        const y = radius * Math.sin(angle);
        (cDiv[i] as HTMLElement).style.transform = `translate(${x}px, ${y}px)`;
      }
    }
  };

  useEffect(() => {
    drawCardCircle();
  }, [isQuestionAsked]);

  return {
    sendQuestion,
    question,
    handleChange,
    handleClick,
    totalCardCount,
    isQuestionAsked,
    stackRef,
    selectedCards,
    maxSelectableCardCount,
    submitTarotCards,
    buttonLoading,
    CardList,
  };
};
export default TarotLogic;
