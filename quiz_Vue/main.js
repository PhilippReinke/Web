// Fisher-Yates shuffle (changes the original array!)
function shuffle(array) 
{
	let currentIndex = array.length,  randomIndex;
	// While there remain elements to shuffle...
	while (currentIndex != 0) 
	{
		// Pick a remaining element...
		randomIndex = Math.floor(Math.random() * currentIndex);
		currentIndex--;

		// And swap it with the current element.
		[array[currentIndex], array[randomIndex]] = [
		array[randomIndex], array[currentIndex]];
	}
	return array;
}

// shuffel questions
const NUM_OF_QUESTIONS = 5; // ideal number of questions for each category

// prepare topics
const topics = ["Willkommen", "Erstes Thema: Natur", "Zweites Thema: Kopfrechnen", "Drittes Thema : Miracoulos", "Auswertung"];
const allQuestArrays = [shuffle(questionsTree), shuffle(questionsMentalCalc), shuffle(questionsMiraculous)];

const root = 
{
	data()
	{
		return {
			// data
			questionsArray: allQuestArrays[0],
			maxQuestions: Math.min(NUM_OF_QUESTIONS, allQuestArrays[0].length),
			points: 0,
			idxQuestion: 0,
			highscoreArray: [],
			nameOfPlayer: "",
			idxTopic: 0,
			topics: topics,
			acheviedPoints: [],
			possiblePoints: [],
			// data for visualisation
			blockAnswsers: false,
			correctAns: 0, 	// 0 = no coloring
			falseAns: 0,
			bDarkCover: false,
			bHighscore: false,
			bQuiz: false,
			bName: true,
			bResults: false,
		};
	},
	mounted()
	{
		axios.get("php/get.php").then(response => (this.highscoreArray = response.data));
	},
	methods:
	{
		handleNextQuestion(answer)
		{
			// wait?
			if(this.blockAnswsers) { return }
			this.blockAnswsers = true;

			// show solution and maybe give point
			this.correctAns = this.questionsArray[this.idxQuestion].correctOption;
			if (this.correctAns != answer) { this.falseAns = answer; }
			else { this.points++; }

			// load next question or next topic or finish game
			if(this.idxQuestion+1 < this.maxQuestions)
			{
				setTimeout(() => { 
					// load next question
					this.correctAns = 0; this.falseAns = 0;
					this.idxQuestion++;
					this.blockAnswsers = false;
				}, 1000);
			}
			else
			{
				setTimeout(() => {
					// save points
					this.possiblePoints.push(this.maxQuestions);
					this.acheviedPoints.push(this.points);
					// was it already the last topic?
					this.idxTopic++;
					if(this.idxTopic < allQuestArrays.length+1)
					{
						// load next topic
						this.questionsArray = allQuestArrays[this.idxTopic-1];
						this.maxQuestions = Math.min(NUM_OF_QUESTIONS, allQuestArrays[this.idxTopic-1].length);
						// reset points, idxQuestion, coloring 
						this.correctAns = 0; this.falseAns = 0;
						this.points = 0; this.idxQuestion = 0;
						this.blockAnswsers = false;
					}
					else
					{
						// overall points
						this.possiblePoints.push(this.possiblePoints.reduce((a, b) => a + b, 0));
						this.acheviedPoints.push(this.acheviedPoints.reduce((a, b) => a + b, 0));
						// finish game, i.e. show points
						this.bQuiz = false;
						this.bResults = true;
						// create new entry in highscore and reload highscore
						dateTime = new Date().toLocaleString();
						axios.get("php/add.php?name="+this.nameOfPlayer+"&points="+this.acheviedPoints[3]+"&max_points="+this.possiblePoints[3]+"&date="+dateTime.substring(0,dateTime.length-3));
						axios.get("php/get.php").then(response => (this.highscoreArray = response.data));
					}					
				}, 1000);
			}
		},
		showHighscore()
		{
		    axios.get("php/get.php").then(response => (this.highscoreArray = response.data));
			this.bDarkCover = true;
			this.bHighscore = true;
		},
		checkName()
		{
			this.bName = false;
			this.bQuiz = true;
			this.idxTopic++;
		},
	},
}
const app = Vue.createApp(root).mount("#app");