import { ITarotCard } from '../../hooks/TarotDetail.logic';

export const getUnselectedTarot = (
  selectableIds: ITarotCard[],
  selectedIds: ITarotCard[]
): ITarotCard => {
  const selectedIdSet = new Set(selectedIds.map((tarot) => tarot.id));

  const unselectedTarots = selectableIds.filter(
    (tarot) => !selectedIdSet.has(tarot.id)
  );

  const randomIndex = Math.floor(Math.random() * unselectedTarots.length);
  return unselectedTarots[randomIndex];
};
