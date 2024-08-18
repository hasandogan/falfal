import { ReactElement } from 'react';
import 'react-toastify/dist/ReactToastify.css';
import Card from '../../components/advanced/Card';
import TarotCard from '../../components/advanced/TarotCard';
import Button from '../../components/basic/Button';
import TextArea from '../../components/basic/Textarea';
import TarotCards from '../../constants/tarotCards';
import TarotLogic from '../../hooks/Tarot.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/tarot.styled';

const Tarot = () => {
  const {
    sendQuestion,
    question,
    handleChange,
    handleClick,
    isQuestionAsked,
    stackRef,
    selectedCards,
    maxSelectableCardCount,
    submitTarotCards,
    buttonLoading,
  } = TarotLogic();

  return (
    <Styled.Tarot>
      <Card className="card-wrapper">
        {!isQuestionAsked ? (
          <Styled.QuestionForm onSubmit={sendQuestion}>
            <TextArea
              id="question"
              name="question"
              placeholder="Sorunuzu yazınız"
              label="Soru"
              value={question}
              onChange={handleChange}
            />
            <Button type="submit">Sor</Button>
          </Styled.QuestionForm>
        ) : (
          <>
            <div className={'tarot-detail-container'}>
              {selectedCards.map((card, index) => (
                <div
                  key={`card-${index}`}
                  className={`card-showcase ${card.result ? '' : 'revert'}`}
                >
                  <img
                    src={card.image}
                    alt={card.name}
                    width={74}
                    height={115}
                  />
                </div>
              ))}
            </div>
            <div className="tarot-container" ref={stackRef}>
              {TarotCards.map((card, index) => (
                <TarotCard
                  key={`${card.front}-${index}`}
                  handleClick={handleClick}
                  totalCount={selectedCards.length}
                />
              ))}
            </div>

            {selectedCards.length === maxSelectableCardCount && (
              <Button
                className={'submit-button'}
                onClick={submitTarotCards}
                disabled={buttonLoading}
                loading={buttonLoading}
              >
                Gönder
              </Button>
            )}
          </>
        )}
      </Card>
    </Styled.Tarot>
  );
};

Tarot.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'TAROT'}>{page}</LoggedInLayout>
);

export default Tarot;
