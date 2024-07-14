import * as Styled from './card.styled';
import { CardProps } from './card.type';

const Card = ({ type, children }: CardProps) => {
  return <Styled.Card type={type}>{children}</Styled.Card>;
};

export default Card;
