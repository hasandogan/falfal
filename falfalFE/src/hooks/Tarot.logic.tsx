import { useRouter } from 'next/router';
import { useEffect, useRef, useState } from 'react';
import { toast } from 'react-toastify';
import TarotCards from '../constants/tarotCards';
import { SendTarot } from '../services/tarot/send-tarot';
import { getUnselectedTarot } from '../utils/helpers/getUnselectedTarot';
import { ITarot } from '../utils/interfaces/ITarot';

const TarotLogic = () => {
  const toRadians = (degrees: number): number => {
    return (degrees * Math.PI) / 180;
  };

  const router = useRouter();
  const maxSelectableCardCount = 7;
  const stackRef = useRef<HTMLDivElement>(null);
  const [isQuestionAsked, setIsQuestionAsked] = useState<boolean>(false);
  const [question, setQuestion] = useState<string>('');
  const [selectedCards, setSelectedCards] = useState<ITarot[]>([]);

  const sendQuestion = () => {
    setIsQuestionAsked(true);
  };

  const handleClick = () => {
    const newCards: ITarot = getUnselectedTarot(TarotCards, selectedCards);
    setSelectedCards((prev) => [...prev, newCards]);
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
    const request = {
      question,
      selectedTarotsCards: selectedCards.map((x) => x.id),
    };
    try {
      const response = await SendTarot(request);
      toast.success(response.message || 'Falin gönderildi');
      router.push('/');
    } catch (error: any) {
      toast.error(error.message || 'Bir problem oluştu');
    }
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
  };
};
export default TarotLogic;
