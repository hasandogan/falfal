import Link from 'next/link';
import * as Styled from './FortuneCard.styled';
import FortuneCardProps from './FortuneCard.type';

const FortuneCard = ({ fortune }: FortuneCardProps) => {
  return (
      <Styled.FortuneCard>
        <Link className="read-more" href={`/${fortune.page}/${fortune.id}`}>
          <div className="header">
            <div className="title">{fortune.type}</div>
            <div className="date">{fortune.date}</div>
          </div>
          {fortune.question && (
              <div className="title">Sorunuz: {fortune.question}</div>
          )}
          <div className="message">{fortune.message}</div>
        </Link>
      </Styled.FortuneCard>
  );
};
export default FortuneCard;
