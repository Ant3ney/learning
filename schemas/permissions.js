export default {
	title: 'Permissions',
	name: 'permissions',
	type: 'document',
	__experimental_actions: [/*'create',*/ 'update', /*'delete',*/ 'publish'],
	fields: [
		{
			title: 'Mode',
			name: 'mode',
			type: 'string',
		},
		{
			title: 'Can anyone use this',
			name: 'cananyoneusethis',
			type: 'boolean',
		},
	],
};
