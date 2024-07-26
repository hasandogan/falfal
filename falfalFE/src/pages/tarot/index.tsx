import { ReactElement } from 'react';
import 'react-toastify/dist/ReactToastify.css';
import Card from '../../components/advanced/Card';
import TarotCard from '../../components/advanced/TarotCard';
import Button from '../../components/basic/Button';
import TextArea from '../../components/basic/Textarea';
import TarotCards from '../../constants/tarotCards';
import TarotLogic from '../../hooks/Tarot.logic';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/tarot.styled';

const Tarot = () => {
  const {
    sendQuestion,
    question,
    handleChange,
    handleClick,
    isQuestionAsked,
    selectedCardCount,
    stackRef,
    getSelectedCards,
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
            <div className="selected-cards">count {selectedCardCount}</div>
            {selectedCardCount === 7 ? (
              <>
                {getSelectedCards().map((card, index) => (
                  <div key={`card-${index}`}>{card.result.name}</div>
                ))}
              </>
            ) : (
              <div className="tarot-container" ref={stackRef}>
                {TarotCards.map((card, index) => (
                  <TarotCard
                    key={`${card.front}-${index}`}
                    handleClick={handleClick}
                    totalCount={selectedCardCount}
                  />
                ))}
              </div>
            )}
          </>
        )}
      </Card>
    </Styled.Tarot>
  );
};

Tarot.getLayout = (page: ReactElement) => <SimpleLayout>{page}</SimpleLayout>;

export default Tarot;
