import { useEffect, useRef, useState } from 'react';
import TarotCards from '../constants/tarotCards';
import { getRandomBoolean } from '../utils/helpers/getRandomBoolean';
import { getUniqueRandomElements } from '../utils/helpers/getUniqueRandomElements';
import { ITarot } from '../utils/interfaces/ITarot';

const TarotLogic = () => {
  const toRadians = (degrees: number): number => {
    return (degrees * Math.PI) / 180;
  };

  const stackRef = useRef<HTMLDivElement>(null);
  const [selectedCardCount, setSelectedCardCount] = useState<number>(0);
  const [isQuestionAsked, setIsQuestionAsked] = useState<boolean>(false);
  const [question, setQuestion] = useState<string>('');

  const sendQuestion = () => {
    setIsQuestionAsked(true);
  };

  const handleClick = () => {
    if (selectedCardCount < 7) {
      setSelectedCardCount(selectedCardCount + 1);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
    const { value } = e.target;
    setQuestion(value);
  };
  const totalCardCount = Array.from({ length: 7 }, (_, i) => i + 1);

  const getSelectedCards = () => {
    const uniqueArray = getUniqueRandomElements(TarotCards, 7) as ITarot[];
    const finalArray = uniqueArray.map((item) => {
      return {
        ...item,
        result: getRandomBoolean() ? item.straight : item.revert,
      };
    });
    return finalArray;
  };

  useEffect(() => {
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
  }, [isQuestionAsked]);

  return {
    sendQuestion,
    question,
    handleChange,
    handleClick,
    totalCardCount,
    isQuestionAsked,
    selectedCardCount,
    stackRef,
    getSelectedCards,
  };
};
export default TarotLogic;
