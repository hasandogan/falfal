import { getTitleByFortuneType } from '@/utils/helpers/getTitleByFortuneType';
import Link from 'next/link';
import * as Styled from './FortuneCard.styled';
import FortuneCardProps from './FortuneCard.type';

const FortuneCard = ({ fortune }: FortuneCardProps) => {
  return (
    <Styled.FortuneCard>
      <div className="header">
        <div className="title">{getTitleByFortuneType(fortune.type)}</div>
        <div className="date">{'fortune.date'}</div>
      </div>
      <div className="message">{fortune.message}</div>
      <Link className="read-more" href={`/tarot/${fortune.id}`}>
        Read More
      </Link>
    </Styled.FortuneCard>
  );
};
export default FortuneCard;
