import FortuneCard from '../FortuneCard';
import FortuneListProps from './FortuneList.type';

const FortuneList = ({ fortunes }: FortuneListProps) => {
  return (
    <>
      {fortunes.map((fortune, index) => (
        <FortuneCard fortune={fortune} key={index} />
      ))}
    </>
  );
};
export default FortuneList;
