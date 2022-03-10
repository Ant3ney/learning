import S from '@sanity/desk-tool/structure-builder';
import React from 'react';

const JsonPreview = ({ document, schemaType }) => {
	return (
		<div>
			<h1>
				JSON Data of {schemaType.title} for "{document.displayed.title}"
			</h1>
			<pre>{JSON.stringify(document.displayed, null, 2)}</pre>
		</div>
	);
};

const OtherComponent = () => {
	return (
		<div>
			<h1>This is the other component</h1>
		</div>
	);
};

export const getDefaultDocumentNode = ({ documentId, schemaType }) => {
	// Return all documents with just 1 view: the form
	if (schemaType === 'post' || documentId === 'siteSettings')
		return S.document().views([S.view.form(), S.view.component(JsonPreview).title('Json Preview'), S.view.form()]);
};

export default () => {
	return S.list()
		.title('Dashbord')
		.items([
			S.listItem()
				.title('Filtered Posts')
				.child(
					S.list()
						.title('Filterd Posts')
						.items([
							S.listItem()
								.title('Posts by Category')
								.child(
									S.documentTypeList('category')
										.title('Posts by Category')
										.child(categoryId => {
											return S.documentList()
												.title('Posts')
												.filter('_type == "post" && $categoryId in categories[]._ref')
												.params({ categoryId: categoryId });
										})
								),
							S.listItem()
								.title('Posts by Author')
								.child(
									S.documentTypeList('author')
										.title('Posts by Author')
										.child(authorId => {
											return S.documentList()
												.title(`Posts from ${authorId}`)
												.filter('_type == "post" && $authorId == author._ref')
												.params({ authorId });
										})
								),
							/* _type == "post" */
						])
				),
			S.divider(),
			S.listItem().title('All posts').child(S.documentList().title('All Posts').filter('_type == "post"')),
			...S.documentTypeListItems(),
			S.listItem().title('Document Type List Test').child(S.documentTypeList('author').title('Authors')),
			S.listItem()
				.title('Document List Test')
				.child(
					S.documentList()
						.filter('_type == "post"')
						.title('Posts')
						.menuItems([
							...S.documentTypeList('post').getMenuItems(),
							/* Note S.menuItemGroup dose not work. Check 
                            https://sanity-io-land.slack.com/archives/C01T1B5GVEG/p1646885227202479
                            TODO to get solution as to how this works in the future */
							S.menuItemGroup().id('TheCrazyItems').title('The Crazy Items'),
							S.menuItem()
								.title('The group Item')
								.action(() => {
									alert('Hello. This group menu item works');
								})
								.group('TheCrazyItems'),
							S.menuItem()
								.title('Orlolololo')

								.action(() => {
									alert('Hello. This works just fine');
								})
								.group('TheCrazyItems')
								.showAsAction(true),
							S.orderingMenuItem({
								title: 'Get the worst items',
								by: [{ field: 'title', direction: 'desc' }],
							}),
						])
				),
			S.listItem().title('Document Type Test').child(S.document().schemaType('author')),
			S.listItem()
				.title('Structure Builder API Methods Test')
				.child(
					S.document()
						.schemaType('post')
						.views([S.view.form(), S.view.component(OtherComponent).title('Other ComponentTest')])
				),
			S.documentListItem().title('Author from document LIst tiem').schemaType('author'),
			S.documentTypeListItem('post').title('Post tests again').id('postvalue'),
		]);
};
