const upvoteApp = {
	data()
	{
		return { submissions: submissions.sort((a,b) => {return b.votes - a.votes;}) };
	},

	methods:
	{
		upvote(submissionId)
		{
			const submission = this.submissions.find((submission) => submission.id === submissionId);
			submission.votes++;
			this.submissions = this.submissions.sort((a,b) => {return b.votes - a.votes;})
		},
	},
};

app = Vue.createApp(upvoteApp).mount("#app");