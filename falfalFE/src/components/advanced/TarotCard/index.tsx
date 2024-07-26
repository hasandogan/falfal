import TarotCardLogic from './tarotcard.logic';
import * as Styled from './tarotcard.styled';
import { TarotCardProps } from './tarotcard.type';

const TarotCard = ({ handleClick, totalCount }: TarotCardProps) => {
  const { isSelected, onClick } = TarotCardLogic({ handleClick, totalCount });

  // @TODO Revert ya da düz durumu düşünülecek
  return (
    <Styled.TarotCard
      onClick={onClick}
      className={`card ${isSelected ? 'selected' : ''}`}
    ></Styled.TarotCard>
  );
};
export default TarotCard;
