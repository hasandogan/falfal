import { useRouter } from 'next/router';
import { ReactElement } from 'react';
import TarotDetailLogic from '../../hooks/TarotDetail.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';

const TarotDetail = () => {
  const router = useRouter();
  const { id } = router.query;

  const { tarot } = TarotDetailLogic(id);

  return (
    <div>
      {tarot?.message}
      <p>Fortune ID: {id}</p>
    </div>
  );
};

export default TarotDetail;

TarotDetail.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'Tarot Detail'}>{page}</LoggedInLayout>
);
