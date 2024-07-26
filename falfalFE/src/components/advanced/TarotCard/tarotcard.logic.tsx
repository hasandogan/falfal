import { useState } from 'react';
import { TarotCardProps } from './tarotcard.type';

const TarotCardLogic = ({ totalCount, handleClick }: TarotCardProps) => {
  const [isSelected, setIsSelected] = useState<boolean>(false);
  const onClick = () => {
    if (totalCount === 7) {
      return;
    }
    if (!isSelected) {
      setIsSelected(true);
      handleClick?.();
    }
  };
  return { onClick, isSelected };
};

export default TarotCardLogic;
