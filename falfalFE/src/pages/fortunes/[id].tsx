import { useRouter } from 'next/router';
import { ReactElement } from 'react';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';

const FortunesDetail = () => {
  const router = useRouter();
  const { id } = router.query;

  return (
    <div>
      <h1>Fortune Detail</h1>
      <p>Fortune ID: {id}</p>
    </div>
  );
};

export default FortunesDetail;

FortunesDetail.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'Fortunes Detail'}>{page}</LoggedInLayout>
);
