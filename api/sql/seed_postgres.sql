INSERT INTO authors (id, author) VALUES
(1, 'Albert Einstein'),
(2, 'Maya Angelou'),
(3, 'Oscar Wilde'),
(4, 'Confucius'),
(5, 'Lex Luthor');

INSERT INTO categories (id, category) VALUES
(1, 'Inspiration'),
(2, 'Motivation'),
(3, 'Humor'),
(4, 'Wisdom'),
(5, 'Power');

INSERT INTO quotes (id, quote, author_id, category_id) VALUES
(1, 'Life is like riding a bicycle. To keep your balance you must keep moving.', 1, 1),
(2, 'Imagination is more important than knowledge.', 1, 4),
(3, 'Strive not to be a success, but rather to be of value.', 1, 2),
(4, 'Weakness of attitude becomes weakness of character.', 1, 4),
(5, 'In the middle of difficulty lies opportunity.', 1, 2),
(6, 'You will face many defeats in life, but never let yourself be defeated.', 2, 2),
(7, 'Try to be a rainbow in someone''s cloud.', 2, 1),
(8, 'Nothing will work unless you do.', 2, 2),
(9, 'If you don''t like something, change it.', 2, 2),
(10, 'We may encounter many defeats but we must not be defeated.', 2, 1),
(11, 'Be yourself; everyone else is already taken.', 3, 3),
(12, 'Always forgive your enemies; nothing annoys them so much.', 3, 3),
(13, 'Experience is simply the name we give our mistakes.', 3, 4),
(14, 'To live is the rarest thing in the world.', 3, 1),
(15, 'I can resist everything except temptation.', 3, 3),
(16, 'It does not matter how slowly you go as long as you do not stop.', 4, 2),
(17, 'Everything has beauty, but not everyone sees it.', 4, 4),
(18, 'Our greatest glory is not in never falling, but in rising every time we fall.', 4, 1),
(19, 'He who learns but does not think is lost.', 4, 4),
(20, 'Wherever you go, go with all your heart.', 4, 1),
(21, 'Power is given only to those who dare to lower themselves to pick it up.', 5, 5),
(22, 'Some people can read War and Peace and come away thinking it is a simple adventure story, Others can read the ingredients on a chewing gum wrapper and unlock the secrets of the universe.', 5, 4),
(23, 'If you want to rule the world, you must be willing to do what others will not.', 5, 5),
(24, 'Brains always beat brawn.', 5, 4),
(25, 'A man who has no limits is no man at all.', 5, 5);

SELECT setval('authors_id_seq', (SELECT MAX(id) FROM authors));
SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));
SELECT setval('quotes_id_seq', (SELECT MAX(id) FROM quotes));