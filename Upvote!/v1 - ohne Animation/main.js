const upvoteApp = {
	data()
	{
		return { submissions: submissions };
	},

	computed: 
	{
		sortedSubmissions()
		{
			return this.submissions.sort((a,b) => {return b.votes - a.votes;});
		},
	},

	methods:
	{
		upvote(submissionId)
		{
			const submission = this.submissions.find((submission) => submission.id === submissionId);
			submission.votes++;
		},
	},
};

app = Vue.createApp(upvoteApp).mount("#app");