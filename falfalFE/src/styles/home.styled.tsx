import styled from 'styled-components';

export const Home = styled.div``;

export const Card = styled.div`
  background: rgba(0, 0, 0, 0.7);
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 16px;
  margin: 16px 0;
  color: #fff;
  max-width: 400px;
  width: 100%;
`;

export const CardTitle = styled.h2`
  font-size: 24px;
  margin-bottom: 8px;
`;

export const CardDate = styled.span`
  font-size: 14px;
  color: #bbb;
  float: right;
`;

export const CardMessage = styled.p`
  font-size: 16px;
  margin-bottom: 16px;
`;

export const CardLink = styled.a`
  color: #007bff;
  cursor: pointer;
  text-decoration: underline;

  &:hover {
    color: #0056b3;
  }
`;
