import S from '@sanity/desk-tool/structure-builder';

export default () => {
	console.log(S.documentTypeListItems());
	return S.list()
		.title('Dashbord')
		.items([
			S.listItem()
				.title('Settings')
				.child(
					S.list()
						.title('Settings')
						.items([
							S.listItem().title('Color').child(S.document().schemaType('colors').documentId('colors')),
							S.listItem().title('Meta').child(S.document().schemaType('meta').documentId('meta')),
							S.listItem()
								.title('Permissions')
								.child(S.document().schemaType('permissions').documentId('permissions')),
						])
				),

			/* Note the below code removes the singleton document
            from being displayed on the first Brand Pane. If you
            where to console.log(S.documentTypeListItems()) it
            would be very clear as to how the following code 
            dose this. */
			...S.documentTypeListItems().filter(
				listItem => !['siteSettings', 'colors', 'permissions', 'meta'].includes(listItem.getId())
			),
		]);
};
