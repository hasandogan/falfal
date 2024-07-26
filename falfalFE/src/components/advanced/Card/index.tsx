import * as Styled from './card.styled';
import { CardProps } from './card.type';

const Card = ({ type, children, className }: CardProps) => {
  return (
    <Styled.Card className={className} type={type}>
      {children}
    </Styled.Card>
  );
};

export default Card;
