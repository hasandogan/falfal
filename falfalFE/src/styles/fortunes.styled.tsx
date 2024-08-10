import styled from 'styled-components';

const Fortunes = styled.div`
  .card {
    padding: 20px;
    .card-top {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin-bottom: 10px;
      .card-top-right {
        .name {
          font-size: 16px;
          line-height: 18px;
          color: #11dce8;
          margin-bottom: 8px;
        }
        .description {
          font-weight: 400;
          font-size: 12px;
          line-height: 15px;
          color: #ffffff;
        }
      }
    }
    .card-bottom {
      text-align: right;
    }
  }
`;

export { Fortunes };
