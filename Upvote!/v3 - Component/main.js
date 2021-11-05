// Create root app
const upvoteRoot = {
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
const app = Vue.createApp(upvoteRoot);

// define a new global component
const submissionComponent = {
	props: ["submission"],
	template: `
		<!-- Bild des Themas -->
		<img v-bind:src="submission.submissionImage" height="64" width="64" style="margin-right: 15px;">
		
		<!-- Text des Themas -->
		<div style="flex: 1;">
			<!-- Titel, Hashtag-Nummer und Votes -->
			<div style="font-weight: bold;">
				<a href="#" class="has-text-info">{{submission.title}}</a>&nbsp;
				<span class="tag"> #{{submission.id}}</span>
				<span class="noselect" style="float:right; cursor: pointer;" v-on:click="$emit('upvote_emit')">
					<i class="fa fa-chevron-up"></i>&nbsp;
					<strong>{{submission.votes}}</strong>
				</span>
			</div>

			<!-- Beschreibung -->
			<div style="margin-top: 0.5em;">
				{{submission.description}}
			</div>

			<!-- Submitted by und Avatar -->
			<div style="font-size: 0.85em; margin-top: 0.9em;">
				Submitted by: 
				<img :src="submission.avatar" height="24" width="24" style="vertical-align:middle;">
			</div>
		</div>
	`,
};
app.component("submission-component", submissionComponent); // global component registration

// mount it
app.mount("#app")