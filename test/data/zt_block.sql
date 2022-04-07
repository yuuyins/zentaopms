truncate `zt_block`;
INSERT INTO `zt_block` (`id`, `account`, `vision`, `module`, `type`, `title`, `source`, `block`, `params`, `order`, `grid`, `height`, `hidden`) VALUES
(102,	'admin',	'rnd',	'my',	'',	'待处理',	'',	'assigntome',	'{\"todoCount\":\"20\",\"taskCount\":\"20\",\"bugCount\":\"20\",\"riskCount\":\"20\",\"issueCount\":\"20\",\"storyCount\":\"20\",\"meetingCount\":\"20\"}',	8,	8,	0,	0),
(101,	'admin',	'rnd',	'my',	'',	'我近期参与的项目',	'project',	'recentproject',	'',	7,	8,	0,	0),
(100,	'admin',	'rnd',	'my',	'',	'我的贡献',	'',	'contribute',	'',	6,	4,	0,	0),
(97,	'admin',	'rnd',	'my',	'',	'流程图',	'',	'flowchart',	'',	3,	8,	0,	0),
(98,	'admin',	'rnd',	'my',	'',	'我的待办',	'todo',	'list',	'{\"count\":\"20\"}',	4,	4,	0,	0),
(99,	'admin',	'rnd',	'my',	'',	'项目统计',	'project',	'statistic',	'{\"count\":\"20\"}',	5,	8,	0,	0),
(95,	'admin',	'rnd',	'my',	'',	'欢迎',	'',	'welcome',	'',	1,	8,	0,	0),
(96,	'admin',	'rnd',	'my',	'',	'最新动态',	'',	'dynamic',	'',	2,	4,	0,	0),
(41,	'admin',	'lite',	'my',	'',	'欢迎',	'',	'welcome',	'',	1,	8,	0,	0),
(42,	'admin',	'lite',	'my',	'',	'最新动态',	'',	'dynamic',	'',	2,	4,	0,	0),
(43,	'admin',	'lite',	'my',	'',	'流程图',	'',	'flowchart',	'',	3,	8,	0,	0),
(44,	'admin',	'lite',	'my',	'',	'我的待办',	'todo',	'list',	'{\"count\":\"20\"}',	4,	4,	0,	0),
(45,	'admin',	'lite',	'my',	'',	'看板列表',	'execution',	'scrumlist',	'{\"count\":\"15\",\"type\":\"doing\",\"orderBy\":\"id_desc\"}',	5,	8,	0,	0),
(46,	'admin',	'lite',	'my',	'',	'我近期参与的项目',	'project',	'recentproject',	'',	7,	8,	0,	0),
(47,	'admin',	'lite',	'my',	'',	'待处理',	'',	'assigntome',	'{\"todoCount\":\"20\",\"taskCount\":\"20\",\"bugCount\":\"20\",\"riskCount\":\"20\",\"issueCount\":\"20\",\"storyCount\":\"20\",\"meetingCount\":\"20\"}',	8,	8,	0,	0),
(48,	'admin',	'lite',	'my',	'',	'项目列表',	'project',	'project',	'{\"orderBy\":\"id_desc\",\"count\":\"15\"}',	10,	8,	0,	0),
(110,	'admin',	'rnd',	'qa',	'',	'待测版本列表',	'qa',	'testtask',	'{\"count\":15,\"orderBy\":\"id_desc\",\"type\":\"wait\"}',	4,	8,	0,	0),
(109,	'admin',	'rnd',	'qa',	'',	'指派给我的用例',	'qa',	'case',	'{\"count\":15,\"orderBy\":\"id_desc\",\"type\":\"assigntome\"}',	3,	4,	0,	0),
(108,	'admin',	'rnd',	'qa',	'',	'指派给我的Bug',	'qa',	'bug',	'{\"count\":15,\"orderBy\":\"id_desc\",\"type\":\"assignedTo\"}',	2,	4,	0,	0),
(107,	'admin',	'rnd',	'qa',	'',	'测试统计',	'qa',	'statistic',	'{\"type\":\"noclosed\",\"count\":\"20\"}',	1,	8,	0,	0),
(53,	'',	'rnd',	'my',	'',	'欢迎',	'',	'welcome',	'',	1,	8,	0,	0),
(54,	'',	'rnd',	'my',	'',	'最新动态',	'',	'dynamic',	'',	2,	4,	0,	0),
(55,	'',	'rnd',	'my',	'',	'流程图',	'',	'flowchart',	'',	3,	8,	0,	0),
(56,	'',	'rnd',	'my',	'',	'我的待办',	'todo',	'list',	'{\"count\":\"20\"}',	4,	4,	0,	0),
(57,	'',	'rnd',	'my',	'',	'项目统计',	'project',	'statistic',	'{\"count\":\"20\"}',	5,	8,	0,	0),
(58,	'',	'rnd',	'my',	'',	'我的贡献',	'',	'contribute',	'',	6,	4,	0,	0),
(59,	'',	'rnd',	'my',	'',	'我近期参与的项目',	'project',	'recentproject',	'',	7,	8,	0,	0),
(60,	'',	'rnd',	'my',	'',	'待处理',	'',	'assigntome',	'{\"todoCount\":\"20\",\"taskCount\":\"20\",\"bugCount\":\"20\",\"riskCount\":\"20\",\"issueCount\":\"20\",\"storyCount\":\"20\",\"meetingCount\":\"20\"}',	8,	8,	0,	0),
(61,	'',	'rnd',	'my',	'',	'项目人力投入',	'project',	'projectteam',	'',	9,	8,	0,	0),
(62,	'',	'rnd',	'my',	'',	'项目列表',	'project',	'project',	'{\"orderBy\":\"id_desc\",\"count\":\"15\"}',	10,	8,	0,	0),
(106,	'admin',	'rnd',	'project',	'waterfall',	'最新动态',	'project',	'projectdynamic',	'',	6,	4,	0,	0),
(105,	'admin',	'rnd',	'project',	'waterfall',	'项目计划',	'project',	'waterfallgantt',	'',	3,	8,	0,	0),
(103,	'admin',	'rnd',	'my',	'',	'项目人力投入',	'project',	'projectteam',	'',	9,	8,	0,	0),
(104,	'admin',	'rnd',	'my',	'',	'项目列表',	'project',	'project',	'{\"orderBy\":\"id_desc\",\"count\":\"15\"}',	10,	8,	0,	0);
